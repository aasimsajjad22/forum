<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use RecordsActivity;
    protected $guarded = [];

    public function favourited()
    {
        return $this->morphTo();
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }
}
