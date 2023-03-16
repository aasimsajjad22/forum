<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    public function store()
    {
        //dd(request()->all());

        $this->validate(request(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:6000',
        ]);

        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);

        return response([], 204);
    }
}
