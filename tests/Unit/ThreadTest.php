<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ThreadTest extends TestCase
{
	protected $thread;

	use RefreshDatabase;

	public function setUp(): void
	{
		parent::setUp();

		$this->thread = factory(Thread::class)->create();		
	}

	/** @test **/
	public function a_thread_has_a_path()
	{
		$thread = factory(Thread::class)->create();

		$this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
	}

	/** @test **/
	public function a_thread_has_a_creator()
	{
		$this->assertInstanceOf(User::class, $this->thread->creator);
	}

	/** @test **/
	public function a_thread_has_replies()
	{
		$this->assertInstanceOf(Collection::class, $this->thread->replies);		
	}

	/** @test **/
	public function a_thread_can_add_a_reply()
	{
		$this->thread->addReply([
			'body' => 'Foobar',
			'user_id' => 1
		]);

		$this->assertCount(1, $this->thread->replies);
	}

	/** @test **/
	public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
	{
		Notification::fake();

		$this->signIn();

		$this->thread->subscribe();

		$this->thread->addReply([
			'body' => 'Foobar',
			'user_id' => 999,
		]);

		Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
	}

	/** @test **/
	public function a_thread_belongs_to_a_channel()
	{
		$thread = factory(Thread::class)->create();

		$this->assertInstanceOf(Channel::class, $thread->channel);
	}

	/** @test **/
	public function a_thread_can_be_subscribed_to()
	{
		$thread = factory(Thread::class)->create();

		$thread->subscribe($userId = 2);

		$this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
	}

	/** @test **/
	public function a_thread_can_be_unsubscribed_to()
	{
		$thread = factory(Thread::class)->create();

		$thread->subscribe($userId = 2);

		$thread->unsubscribe($userId = 2);

		$this->assertCount(0, $thread->subscriptions);
	}

	/** @test **/
	public function it_knows_if_the_auth_user_is_subscribed_to_it()
	{
		$thread = factory(Thread::class)->create();

		$this->signIn();

		$this->assertFalse($thread->isSubscribedTo);

		$thread->subscribe();

		$this->assertTrue($thread->isSubscribedTo);
	}

	/** @test **/
	public function a_thread_can_check_if_authenticated_user_read_all_replies()
	{
		$this->signIn();

		$thread = factory(Thread::class)->create();

		tap(auth()->user(), function($user) use($thread) {
			$this->assertTrue($thread->hasUpdatesFor($user));
			
			$user->read($thread);

			$this->assertFalse($thread->hasUpdatesFor($user));
		});
	}

	/** @test **/
	public function a_threads_body_is_sanitized_automatically()
	{
		$thread = factory(Thread::class)->make(['body' => '<script>alert("Bad!")</script><p>Good</p>']);

		$this->assertEquals('<p>Good</p>', $thread->body);
	}
}
