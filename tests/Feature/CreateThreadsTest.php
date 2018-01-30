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
        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());
        //then i should see a new thread page
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
     * @test
     */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /**
     * @test
     */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /**
     * @test
     */
    function guest_cannot_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function a_thread_can_be_deleted()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /**
     * @test
     */
    function threads_can_be_deleted_by_users_with_permission()
    {
        //TODO:
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', $overrides);
        return $this->post('/threads', $thread->toArray());
    }
}
