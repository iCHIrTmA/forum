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

    /** @test **/
    public function unauthorized_users_cannot_delete_replies()
    {
        $reply = factory(Reply::class)->create();

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn();

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test **/
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = factory(Reply::class)->create(['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test **/
    public function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = factory(Reply::class)->create(['user_id' => auth()->id()]);

        $updatedReply = 'You been changed fool.';
        $this->patch("replies/{$reply->id}", ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $updatedReply,
        ]);
    }

    /** @test **/
    public function unauthorized_users_cannot_update_replies()
    {
        $reply = factory(Reply::class)->create();

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn();

        $this->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }
}
