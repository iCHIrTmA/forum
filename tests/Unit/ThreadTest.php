<?php

namespace Tests\Unit;

use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadTest extends TestCase
{
	use RefreshDatabase;

	/** @test **/
	public function a_thread_has_replies()
	{
		$thread = factory(Thread::class)->create();

		$this->assertInstanceOf(Collection::class, $thread->replies);		
	}

	/** @test **/
	public function a_thread_has_a_creator()
	{
		$thread = factory(Thread::class)->create();

		$this->assertInstanceOf(User::class, $thread->creator);
	}
}
