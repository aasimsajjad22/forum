<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;
    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['isSubscribedTo'];


    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

//        static::addGlobalScope('replyCount', function ($builder) {
//            $builder->withCount('replies');
//        });


        static::deleting(function ($thread) {
            //$thread->replies()->delete();

            //$thread->replies->each(function ($reply) {
            //    $reply->delete();
            //});

            $thread->replies->each->delete();


        });

//      static::addGlobalScope('creator', function ($builder) {
//          $builder->with('creator');
//      });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        // prepare notification for all subscribers ...

        //$this->notifySubscribers($reply);

//        $this->subscriptions
//            ->filter(function ($sub) use ($reply) {
//                return $sub->user_id != $reply->user_id;
//            })
//            ->each->notify($reply);

//            ->each(function ($sub) use ($reply) {
//                $sub->user->notify(new ThreadWasUpdated($this, $reply));
//            });

//        foreach ($this->subscriptions as $subscription) {
//            if ($subscription->user_id != $reply->user_id) {
//                $subscription->user->notify(new ThreadWasUpdated($this, $reply));
//            }
//        }

        return $reply;
    }

    /**
     * Notify all thread subscribers about a new reply.
     *
     * @param \App\Reply $reply
     */
//    public function notifySubscribers($reply)
//    {
//        $this->subscriptions
//            ->where('user_id', '!=', $reply->user_id)
//            ->each
//            ->notify($reply);
//    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    /**************   RELATIONSHIP METHODS    *********************/
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /******************   AdditionalAttributes    *********************/


    /**
     * Determine if the current user is subscribed to the thread.
     * @return boolean
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    /******************   QueryScopes    *********************/

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
