<?php

namespace App\Events;

use App\ProjectKey;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectKeyAttachedToUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $projectKey;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param ProjectKey $projectKey
     * @param User $user
     */
    public function __construct(ProjectKey $projectKey, User $user)
    {
        $this->projectKey = $projectKey;
        $this->user = $user;
    }
}
