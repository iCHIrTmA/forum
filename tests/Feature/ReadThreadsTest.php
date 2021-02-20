<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test **/
    public function a_user_can_view_all_threads()
    {

        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test **/
    public function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test **/
    public function a_user_can_read_replies_associated_with_a_thread()
    {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())->assertSee($reply->body);
    }

    /** @test **/
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = factory(Channel::class)->create();

        $threadInChannel = factory(Thread::class)->create(['channel_id' => $channel->id]);

        $threadNotInChannel = factory(Thread::class)->create();

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test **/
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(factory(User::class)->create(['name' => 'JohnDoe']));

        $threadByJohn = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $threadNotByJohn = factory(Thread::class)->create();

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }    
}
