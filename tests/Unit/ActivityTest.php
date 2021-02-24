<?php

namespace Tests\Unit;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
	use RefreshDatabase;

	/** @test **/
	public function it_records_an_activity_when_a_thread_is_created()
	{
		$this->signIn();

		$thread = factory(Thread::class)->create();

		$this->assertDatabaseHas('activities', [
			'type' => 'created_thread',
			'user_id' => auth()->id(),
			'subject_id' => $thread->id,
			'subject_type' => Thread::class,			
		]);
	}
}
