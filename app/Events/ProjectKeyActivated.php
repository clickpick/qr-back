<?php

namespace App\Events;

use App\ProjectKey;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectKeyActivated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $projectKey;

    /**
     * Create a new event instance.
     *
     * @param ProjectKey $projectKey
     */
    public function __construct(ProjectKey $projectKey)
    {
        $this->projectKey = $projectKey;
    }
}
