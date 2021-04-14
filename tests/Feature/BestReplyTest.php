<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test **/
    public function a_thread_creator_may_mark_any_reply_as_best_reply()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);
        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', [$replies[1]->id]));
        // dd($replies[1]->fresh()->thread);
        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test **/
    public function only_the_thread_creator_may_mark_a_reply_as_best()
    {
        // $this->withoutExceptionHandling();
        $this->signIn();

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

        $this->signIn(factory(User::class)->create());

        $this->postJson(route('best-replies.store', [$replies[1]->id]))
            ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }
}
