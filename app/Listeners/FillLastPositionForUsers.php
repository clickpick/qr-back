<?php

namespace App\Listeners;

use App\Events\CityCoordsFilled;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FillLastPositionForUsers implements ShouldQueue
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
     * @param CityCoordsFilled $event
     * @return void
     */
    public function handle(CityCoordsFilled $event)
    {
        $city = $event->city;

        User::where('city_id', $city->id)->whereNull('last_position')->update([
            'last_position' => $city->center
        ]);
    }
}
