<?php

namespace App\Listeners;

use App\Events\CityCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FillCityCoords implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param CityCreated $event
     * @return void
     */
    public function handle(CityCreated $event)
    {
        $city = $event->city;
        $city->fillCoords();
    }
}
