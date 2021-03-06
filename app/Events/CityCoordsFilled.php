<?php

namespace App\Events;

use App\City;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityCoordsFilled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $city;

    /**
     * Create a new event instance.
     *
     * @param City $city
     */
    public function __construct(City $city)
    {
        $this->city = $city;
    }
}
