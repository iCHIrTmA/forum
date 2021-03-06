<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Exception;
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

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test **/
    public function a_reply_requires_a_body()
    {
        // $this->withoutExceptionHandling();
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make(['body' => null]);

        // $this->expectException(\Exception::class);

        $this->json('POST',$thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
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
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
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

    /** @test **/
    public function spam_replies_may_not_be_stored()
    {
        // $this->withoutExceptionHandling();
        $this->signIn();

        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->make([
            'body' => 'A spam'
        ]);

        // $this->expectException(\Exception::class);

        $this->json('POST', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }

    /** @test **/
    public function users_may_reply_a_maximum_of_once_per_minute()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make(['body' => 'simple reply']);

        $this->json('POST', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(201);

        $this->json('POST', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(429);

        $this->json('POST', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(429);
    }
}
