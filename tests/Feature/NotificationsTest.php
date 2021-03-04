<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function notification_is_prepared_when_scubscribed_thread_has_new_reply_not_from_current_user()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create()->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here',
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => factory(User::class)->create(),
            'body' => 'Some reply here',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test **/
    public function a_user_can_clear_a_notification()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = factory(Thread::class)->create()->subscribe();

        $thread->addReply([
            'user_id' => factory(User::class)->create(),
            'body' => 'Some reply here',
        ]);

        $this->assertCount(1, auth()->user()->unreadNotifications);

        $user = auth()->user();

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
