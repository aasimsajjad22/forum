<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favouritable, RecordsActivity;
    protected $guarded = [];

    protected $with = ['owner', 'favourites'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
        });
    }


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['favouritesCount', 'isFavourited'];

    /**
     * Determine if the reply was just published a moment ago.
     *
     * @return bool
     */
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path() . "#reply_{$this->id}";
    }

}
