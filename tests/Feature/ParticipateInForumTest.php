<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
   /**
    * @test
    */
   function an_authenticated_user_may_participate_in_forum_threads()
   {
       $this->be(create('App\User'));
       $thread = create('App\Thread');
       $reply = make('App\Reply');
       $this->post($thread->path() . '/replies', $reply->toArray());
       $this->get($thread->path())->assertSee($reply->body);
   }

   /** @test */
   function unauthenticated_users_may_not_add_replies()
   {
       $this->withExceptionHandling()
            ->post('threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
   }
}
