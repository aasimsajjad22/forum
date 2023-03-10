<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class FavouritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $reply->favourite();
        return back();
    }

    /**
     * Delete the favorite.
     *
     * @param Reply $reply
     */
    public function destroy(Reply $reply)
    {
        $reply->unfavourite();
    }
}
