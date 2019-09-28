<?php

namespace App\Providers;

use App\Events\ProjectCreated;
use App\Events\UserCreated;
use App\Listeners\FillPersonalDataFromVk;
use App\Listeners\GenerateSymbolsForProject;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserCreated::class => [
            FillPersonalDataFromVk::class
        ],
        ProjectCreated::class => [
            GenerateSymbolsForProject::class
        ],

        SocialiteWasCalled::class => [
            'SocialiteProviders\\VKontakte\\VKontakteExtendSocialite@handle',
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
