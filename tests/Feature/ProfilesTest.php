<?php

namespace Tests\Feature;


use Tests\TestCase;

class ProfilesTest extends TestCase
{
    /**
     * @test
     */
    public function a_user_has_a_profile()
    {
        $user = create('App\User');

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    /**
     * @test
     */
    public function profiles_display_all_threads_created_by_the_assoc_user()
    {
        $user = create('App\User');

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->get("/profiles/{$user->name}")
            ->assertSee($thread->title)
            ->assertSee($thread->body);

    }
}