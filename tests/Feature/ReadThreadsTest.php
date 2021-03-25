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

    /** @test **/
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = factory(Thread::class)->create();
        factory(Reply::class, 2)->create(['thread_id' => $threadWithTwoReplies->id]);

        $threadWithThreeReplies = factory(Thread::class)->create();
        factory(Reply::class, 3)->create(['thread_id' => $threadWithThreeReplies->id]);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();
        // dd($response);
        $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));
    }
    /** @test **/
    public function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $threadWithReply = factory(Thread::class)->create();
        factory(Reply::class)->create(['thread_id' => $threadWithReply->id]);

        $response = $this->getJson('threads?unanswered=1')->json();
        // dd($response);
        $this->assertCount(1, $response['data']);
    }   

    /** @test **/
    public function a_user_can_request_all_replies_from_a_thread()
    {
        $thread = factory(Thread::class)->create();

        factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertEquals(2, $response['total']);
    }
}
