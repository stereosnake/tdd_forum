<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class ReadThreadsTest extends TestCase
{
    protected $thread;
    protected $reply;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
        $this->reply = create('App\Reply');
    }

    /**
     * @test
     */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())->assertSee($this->thread->title);
    }

    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = $this->reply->create(['thread_id' => $this->thread->id]);

        $this->get('/threads/' . $this->thread->id)->assertSee($reply->body);
    }
}
