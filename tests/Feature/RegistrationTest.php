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

    /** @test **/
    public function users_can_fully_confirm_their_email_address()
    {
        $this->post('/register', [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'foobarasdas21321dsa',
            'password_confirmation' => 'foobarasdas21321dsa',    
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $response = $this->get('/register/confirm?token=' . $user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect('/threads');
    }
}
