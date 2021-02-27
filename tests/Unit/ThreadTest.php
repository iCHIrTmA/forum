<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
	public function a_thread_can_make_a_string_path()
	{
		$thread = factory(Thread::class)->create();

		$this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
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
}
