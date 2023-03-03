<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourited');
    }

    public function favourite()
    {
        $attribute = ['user_id' => auth()->id()];
        if (!$this->favourites()->where($attribute)->exists()) {
            $this->favourites()->create($attribute);
        }
    }
}
