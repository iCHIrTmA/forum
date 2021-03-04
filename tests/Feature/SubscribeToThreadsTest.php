<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_subscribe_to_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = factory(Thread::class)->create();

        $this->post($thread->path() . '/subscriptions');

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here',
        ]);

        $this->assertCount(1, auth()->user()->notifications);
    }

    /** @test **/
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = factory(Thread::class)->create();

        $thread->subscribe();
        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
