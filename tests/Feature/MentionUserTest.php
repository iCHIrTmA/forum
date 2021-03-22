<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MentionUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function mentioned_users_in_a_reply_are_notified()
    {
        $this->withoutExceptionHandling();
        $john = factory(User::class)->create(['name' => 'JohnDoe']);

        $this->signIn($john);

        $jenny = factory(User::class)->create(['name' => 'JennyDoe']);

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make(['body' => '@JennyDoe and @FrankieDoe look at this']);

        $this->json('POST', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jenny->notifications);
    }

    /** @test **/
    public function it_can_fetch_all_users_starting_with_given_characters()
    {
        factory(User::class)->create(['name' => 'johndoe']);
        factory(User::class)->create(['name' => 'johnnydoe']);
        factory(User::class)->create(['name' => 'jennydoe']);

        $results = $this->json('GET', '/api/users' , ['name' => 'john']);

        $this->assertCount(2, $results->json());
    }
}
