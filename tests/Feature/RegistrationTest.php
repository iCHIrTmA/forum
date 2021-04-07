<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /** @test **/
    public function a_confirmation_is_sent_upon_registration()
    {
        Mail::fake();

        event(new Registered($user = factory(User::class)->create()));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }
}
