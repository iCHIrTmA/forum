<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_has_a_profile()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->get('profiles/' . $user->name)
            ->assertSee($user->name);
    }  

    /** @test **/
    public function profiles_display_all_threads_created_by_the_created_user()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->get('profiles/' . auth()->user()->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
