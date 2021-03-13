<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyTest extends TestCase
{
	use RefreshDatabase;

	/** @test **/
	public function it_has_an_owner()
	{
		$reply = factory(Reply::class)->create();

		$this->assertInstanceOf(User::class, $reply->owner);
	}

	/** @test **/
	public function it_knows_if_it_was_just_published()
	{
		$reply = factory(Reply::class)->create();

		$this->assertTrue($reply->wasJustPublished());

		$reply->created_at = Carbon::now()->subMonth();
		
		$this->assertFalse($reply->wasJustPublished());
	}


}
