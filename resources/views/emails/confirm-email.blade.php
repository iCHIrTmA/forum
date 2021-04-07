@component('mail::message')
# One Last Step

We just need you to confirm your email address.

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Confirm email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
