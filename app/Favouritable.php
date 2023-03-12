<?php

namespace App;
trait Favouritable {


    protected static function bootFavouritable()
    {
        static::deleting(function ($model) {
            //$model->favourites()->delete();
            $model->favourites->each->delete();
        });
    }
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


    /**
     * Unfavorite the current reply.
     */
    public function unfavourite()
    {
        $attributes = ['user_id' => auth()->id()];

        //$this->favourites()->where($attributes)->delete();
        $this->favourites()->where($attributes)->each(function ($model) {
            $model->delete();
        });
    }


    /**
     * Fetch the favorited status as a property.
     *
     * @return bool
     */
    public function getIsFavouritedAttribute()
    {
        return $this->isFavourited();
    }
}