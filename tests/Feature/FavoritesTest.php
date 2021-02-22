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
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();
        
        $reply = factory(Reply::class)->create();

        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

}
