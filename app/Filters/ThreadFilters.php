<?php

namespace App\Filters;

use App\User;
use Symfony\Component\HttpFoundation\Request;

class ThreadFilters extends Filters {

    protected $filters = ['by', 'popular'];

    /**
     * Filters the query by username
     * @param $username
     * @param $builder
     * @return mixed
     */
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    public function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }
}
