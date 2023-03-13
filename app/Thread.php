<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;
    protected $guarded = [];

    protected $with = ['creator', 'channel'];


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

    public function addReply($reply)
    {
        return $this->replies()->create($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
