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
        $this->thread = make('App\Thread');
    }

    /** @test */
    function guest_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/threads', $this->thread->toArray());
    }

    /** @test */
    function guests_cannot_see_the_create_thread_page()
    {
        $this->withExceptionHandling()->get(Thread::CREATE_PATH)->assertRedirect('login');
    }

    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        //given i am an logged in user
        $this->signIn();
        //when i create a new thread

        $this->post('/threads', $this->thread->toArray());
        //then i should see a new thread page
        $this->get($this->thread->path())
            ->assertSee($this->thread->title)
            ->assertSee($this->thread->body);
    }
}
