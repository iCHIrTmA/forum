<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setup(): void
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }

    /** @test **/
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());

        $thread = factory(Thread::class)->create();

        $this->call('GET', $thread->path());

        $this->assertCount(1, $trending = $this->trending->get()); 
        
        $this->assertEquals($thread->title, $trending[0]->title); 
    }

    // /** @test **/
    // public function another_test()
    // {

    // }
}
