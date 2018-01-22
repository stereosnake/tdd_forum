<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\App;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    /**
     * @test
     */
    public function guests_cannot_favorite_replies()
    {
        $this->withExceptionHandling()
             ->post('/replies/1/favorites')
             ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_any_reply()
    {
        $reply = create('App\Reply');

        $this->signIn()
             ->post('/replies/' . $reply->id . '/favorites');
        $this->assertCount(1, $reply->favorites);
    }

    /**
     * @test
     */
    function an_authenticated_user_may_only_favorite_reply_once()
    {
        $reply = create('App\Reply');

        $this->signIn();
        $this->post('/replies/' . $reply->id . '/favorites');
        $this->post('/replies/' . $reply->id . '/favorites');
        $this->assertCount(1, $reply->favorites);
    }
}