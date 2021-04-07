<?php

namespace App\Providers;

use App\Providers\NotifyMentionedUsers;
use App\Providers\NotifySubscribers;
use App\Providers\SendEmailConfirmationRequest;
use App\Providers\ThreadReceivedNewReply;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ThreadReceivedNewReply::class => [
            NotifyMentionedUsers::class,
            NotifySubscribers::class,
        ],

        Registered::class => [
            SendEmailConfirmationRequest::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
