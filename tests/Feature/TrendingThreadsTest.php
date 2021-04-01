<?php

namespace Tests\Feature;

use App\Thread;
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

        Redis::del('trending_threads');
    }

    /** @test **/
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty(Redis::zrevrange('trending_threads', 0 , -1));

        $thread = factory(Thread::class)->create();

        $this->call('GET', $thread->path());

        $trending = Redis::zrevrange('trending_threads', 0 , -1);

        $this->assertCount(1, $trending); 
        
        $this->assertEquals($thread->title, json_decode($trending[0])->title); 
    }

    /** @test **/
    public function another_test()
    {

    }
}
