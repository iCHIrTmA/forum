<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);

        $search = 'foobar';

        factory(Thread::class, 2)->create();
        factory(Thread::class, 2)->create(['body' => "A thread with the {$search} term."]);

        do {
            sleep(.25);
            $results = $this->getJson("/threads/search?q={$search}")->json();
        } while (empty($results));

        $this->assertCount(2, $results['data']);

        Thread::latest()->take(4)->unsearchable();
    }
}
