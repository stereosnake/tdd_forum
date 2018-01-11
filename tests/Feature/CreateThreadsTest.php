<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
    protected $thread;

    protected function setUp()
    {
        parent::setUp();
    }

    /** @test */
    function guest_may_not_create_threads()
    {
        $this->withExceptionHandling();
        $this->get(Thread::CREATE_PATH)
            ->assertRedirect('/login');

        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        //given i am an logged in user
        $this->signIn();
        //when i create a new thread
        $thread = create('App\Thread');

        $this->post('/threads', $thread->toArray());
        //then i should see a new thread page
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
