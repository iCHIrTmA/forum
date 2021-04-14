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

	/** @test **/
	public function it_can_detect_mentioned_users_in_the_body()
	{
		$reply = factory(Reply::class)->create(['body' => '@JennyDoe wants to talk to @JohnDoe']);

		$this->assertEquals(['JennyDoe', 'JohnDoe'], $reply->mentionedUsers());
	}

	/** @test **/
	public function it_wraps_mentioned_usernames_within_anchor_tags()
	{
		$reply = new Reply(['body' => 'Hello @JennyDoe.']);

		$this->assertEquals('Hello <a href="http://localhost/Laravel/forum/public/profiles/JennyDoe">@JennyDoe</a>.', $reply->body);
	}

	/** @test **/
	public function it_knows_if_it_is_the_best_reply()
	{
		$reply = factory(Reply::class)->create();

		$this->assertFalse($reply->isBest());

		$reply->thread->update(['best_reply_id' => $reply->id]);

		$this->assertTrue($reply->isBest());		
	}
}
