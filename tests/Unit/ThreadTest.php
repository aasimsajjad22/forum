<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test  */
    public function a_thread_has_replies()
    {
        $thread = factory('App\Thread')->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $thread->replies);
    }

    /** @test  */
    public function a_thread_has_a_creator()
    {
        $thread = factory('App\Thread')->create();

        $this->assertInstanceOf('App\User', $thread->creator);
    }
}