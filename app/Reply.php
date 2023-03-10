<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favouritable, RecordsActivity;
    protected $guarded = [];

    protected $with = ['owner', 'favourites'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['favouritesCount', 'isFavourited'];

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
