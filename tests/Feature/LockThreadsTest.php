<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function non_administrator_may_not_lock_thread()
    {
        // $this->withoutExceptionHandling();

        $this->signIn();

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))->assertStatus(403);

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test **/
    public function an_administrator_can_lock_threads()
    {
        $this->signIn(factory(User::class)->states('administrator')->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))->assertStatus(200);

        $this->assertTrue(!! $thread->fresh()->locked);
    }

    /** @test **/
    public function an_administrator_can_unlock_threads()
    {
        $this->withoutExceptionHandling();
        $this->signIn(factory(User::class)->states('administrator')->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id(), 'locked' => true]);

        $this->assertTrue($thread->fresh()->locked);

        $this->delete(route('locked-threads.destroy', $thread))->assertStatus(200);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test **/
    public function once_locked_thread_may_not_received_any_replies()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['locked' => true]);

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id(),
        ])->assertStatus(422);
    }


}
