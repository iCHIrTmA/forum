<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInForum extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->post('threads/somechannel/1/replies', [])->assertRedirect('/login');
    }

    /** @test **/
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make();
        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test **/
    public function a_reply_requires_a_body()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make(['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
