<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use App\Activity;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function guests_may_not_create_threads()
    {        
        $this->get('/threads/create')->assertRedirect('/login');

        $this->post('/threads', [])->assertRedirect('/login');
    }

    /** @test **/
    public function authenticated_users_must_first_confirm_their_email_before_creating_threads()
    {
        $this->publishThread()
            ->assertRedirect('/threads')
            ->assertSessionHas('flash');
    }    

    /** @test **/
    public function an_authenticated_user_can_create_threads()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = factory(Thread::class)->make();

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test **/
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test **/
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test **/
    public function a_thread_requires_a_valid_channel()
    {
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => 99])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test **/
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = factory(Thread::class)->create();

        $this->delete($thread->path())
            ->assertRedirect('login');

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);
    }

    /** @test **/
    public function authorized_users_can_delete_threads()
    {
        // $this->withoutExceptionHandling();
        $this->signIn();

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $this->delete($thread->path());

        $this->assertEquals(0, Activity::count());
    }

    // /** @test **/
    // public function threads_may_only_be_deleted_by_those_who_have_permission()
    // {
    //     // TODO
    // }

    public function publishThread($overrides = [])
    {
        $this->signIn();

        $thread = factory(Thread::class)->make($overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
