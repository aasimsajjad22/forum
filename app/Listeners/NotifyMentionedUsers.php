<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     * @param  ThreadReceivedNewReply $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {

        User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });

//        collect($event->reply->mentionedUsers())
//            ->map(function ($name) {
//                return User::where('name', $name)->first();
//            })
//            ->filter()
//            ->each(function ($user) use ($event) {
//                $user->notify(new YouWereMentioned($event->reply));
//            });
    }
}
