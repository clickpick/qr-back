<?php

namespace App\Providers;

use App\Events\ProjectCreated;
use App\Events\ProjectHasFinished;
use App\Events\ProjectKeyActivated;
use App\Events\UserCreated;
use App\Listeners\CheckAvailableCheats;
use App\Listeners\CheckProjectIsFinished;
use App\Listeners\FillPersonalDataFromVk;
use App\Listeners\GenerateSymbolsForProject;
use App\Listeners\SendProjectFinishedNotifications;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
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

        ProjectKeyActivated::class => [
            CheckProjectIsFinished::class,
            CheckAvailableCheats::class
        ],

        ProjectHasFinished::class => [
            SendProjectFinishedNotifications::class
        ]
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
