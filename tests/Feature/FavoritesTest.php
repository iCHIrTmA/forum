<?php

namespace Tests\Feature;

use App\Reply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function guests_cannot_favorite_anything()
    {
        $this->post('replies/1/favorites')
            ->assertRedirect('login');
    }

    /** @test **/
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = factory(Reply::class)->create();

        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');            
        } catch (\Exception $e) {
            $this->fail('Did not see that coming');
        }

        $this->assertCount(1, $reply->favorites);
    }

    /** @test **/
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = factory(Reply::class)->create();

        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

}
