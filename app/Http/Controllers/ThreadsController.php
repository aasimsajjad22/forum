<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Inspections\Spam;
use App\Thread;
use App\Filters\ThreadFilters;
use App\Trending;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {

        $threads = $this->getThreads($filters, $channel);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    public function create()
    {
        return view('threads.create');
    }

    /**
     * Update the given thread.
     * @param string $channel
     * @param Thread $thread
     */
    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->update(request()->validate([
            'title' => 'required',
            'body' => 'required'
        ]));

        return $thread;
    }

    public function store(Request $request, Spam $spam)
    {
        request()->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $spam->detect(request('body'));

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
        ]);


        if (request()->wantsJson()) {
            return response($thread, 201);
        }

        return redirect($thread->path())->with('flash', 'Your thread has been published!');
    }

    public function show($channel, Thread $thread, Trending $trending)
    {

        // record that user visited page
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->visits()->record();

        return view('threads.show', compact('thread'));

        /* return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(20)
        ]); */
    }

    /**
     * @param ThreadFilters $filters
     * @param Channel $channel
     * @return mixed
     */
    public function getThreads(ThreadFilters $filters, Channel $channel)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        return $threads->paginate(10);
    }

    public function destroy($channelId, Thread $thread)
    {
        //$thread->replies()->delete();

        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }
}