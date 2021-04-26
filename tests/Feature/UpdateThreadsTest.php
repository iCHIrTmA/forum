<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test **/
    public function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed',
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed',
        ])->assertSessionHasErrors('title');
    }

    /** @test **/
    public function unauthorized_users_may_not_update_threads()
    {
        $thread = factory(Thread::class)->create(['user_id' => factory(User::class)->create()]);

        $this->patch($thread->path(), [])->assertStatus(403);
    }

    /** @test **/
    public function a_thread_can_be_updated_by_its_creator()
    {
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body' => 'Changed body',
        ]);

        tap($thread->fresh(), function ($thread) {
            $this->assertEquals('Changed', $thread->title); 
            $this->assertEquals('Changed body', $thread->body);
        });
    }
}
