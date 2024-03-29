<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Requests\CreatePostRequest;
use App\Thread;
use Illuminate\Support\Facades\Gate;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Fetch all relevant replies.
     * @param int    $channelId
     * @param Thread $thread
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        if ($thread->locked) {
            return response('Thread is locked', 422);
        }

        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');


//        if (Gate::denies('create', new Reply)) {
//            return response(
//                'You are posting too frequently. Please take a break. :)', 429
//            );
//        }

//        try{
//            $this->validate(request(), ['body' => 'required|spamfree']);
//
//            $reply = $thread->addReply([
//                'body' => request('body'),
//                'user_id' => auth()->id()
//            ]);
//        } catch (\Exception $e) {
//            return response(
//                'Sorry, your reply could not be saved at this time.', 422
//            );
//        }

//        return $reply->load('owner');
    }

    public function destroy(Reply $reply)
    {

        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    /**
     * Update an existing reply.
     *
     * @param Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        request()->validate(['body' => 'required|spamfree']);

        $reply->update(request(['body']));

        //$reply->update(request()->validate(['body' => 'required|spamfree']));

//        try {
//            $this->validate(request(), ['body' => 'required|spamfree']);
//
//            $reply->update(request(['body']));
//        } catch (\Exception $e) {
//            return response(
//                'Sorry, your reply could not be saved at this time.', 422
//            );
//        }
    }

    /**
     * Validate the incoming reply.
     */
    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }
}