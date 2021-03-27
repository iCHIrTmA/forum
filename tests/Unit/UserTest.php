<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
	use RefreshDatabase;

	/** @test **/
	public function a_user_can_fetch_their_most_recent_reply()
	{
		$user = factory(User::class)->create();
		$reply = factory(Reply::class)->create(['user_id' => $user->id]);

		$this->assertEquals($reply->id, $user->lastReply->id);
	}

	/** @test **/
	public function a_user_can_determine_avatar_path()
	{
		$user = factory(User::class)->create();

		$this->assertEquals('avatars/default.jpg', $user->avatar());

		$user->avatar_path = 'avatars/me.jpg';

		$this->assertEquals('avatars/me.jpg', $user->avatar());
	}
}
