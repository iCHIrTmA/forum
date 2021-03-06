<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test **/
    public function it_checks_for_invalid_keywords()
    {
    	$this->withoutExceptionHandling();

    	$spam = new Spam();

    	$this->assertFalse($spam->detect('Innocent Reply'));

    	$this->expectException(\Exception::class);

    	$spam->detect('A spam');
    }

    /** @test **/
    public function it_checks_for_key_held_down()
    {
    	$spam = new Spam();

    	$this->expectException(\Exception::class);

    	$spam->detect('Generic reply aaaaaaaaaa');
    }
}
