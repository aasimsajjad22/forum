<?php

namespace App;
trait Favouritable {

    public function favourite()
    {
        $attribute = ['user_id' => auth()->id()];
        if (!$this->favourites()->where($attribute)->exists()) {
            $this->favourites()->create($attribute);
        }
    }

    public function getFavouritesCountAttribute()
    {
        return $this->favourites->count();
    }

    public function isFavourited()
    {
        return !!$this->favourites->where('user_id', auth()->id())->count();
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourited');
    }
}