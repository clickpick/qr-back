<?php

namespace App\Listeners;

use App\Events\ProjectKeyAttachedToUser;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAboutRareProjectKey implements ShouldQueue
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
     * @param ProjectKeyAttachedToUser $event
     * @return void
     */
    public function handle(ProjectKeyAttachedToUser $event)
    {
        $user = $event->user;
        $projectKey = $event->projectKey;
        $project = $projectKey->project;

        if ($project->is_finished) {
            return;
        }

        if (!$projectKey->isRare()) {
            return;
        }

        $friends = $user->getFriends();

        if (!$friends) {
            return;
        }

        $friends->each(function (User $user) use ($projectKey) {

            if ($user->isWinner()) {
                return;
            }

            if ($user->hasActivatedProjectKey($projectKey)) {
                return;
            }

            $user->sendPush('У твоего друга только что появился секретный код! Поторопись, найди его быстрее других!');
        });
    }
}
