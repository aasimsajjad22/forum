<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavouritesTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */

    /** @test  */
    public function an_authenticated_user_can_favourite_any_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');

        $this->post('replies/' . $reply->id . '/favourites');
        $this->assertCount(1, $reply->favourites);
    }

    /** @test  */
    public function an_authenticated_user_may_favourite_reply_once()
    {
        $this->signIn();
        $reply = create('App\Reply');

        try {
            $this->post('replies/' . $reply->id . '/favourites');
            $this->post('replies/' . $reply->id . '/favourites');
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert same record twice');
        }

        $this->assertCount(1, $reply->favourites);
    }
}
