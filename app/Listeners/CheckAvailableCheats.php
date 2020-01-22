<?php

namespace App\Listeners;

use App\Events\ProjectKeyActivated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckAvailableCheats
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
     * @param ProjectKeyActivated $event
     * @return void
     */
    public function handle(ProjectKeyActivated $event)
    {
        $user = $event->user;
        $projectKet = $event->projectKey;
        $project = $projectKet->project;
        $projectKeysCount = $project->projectKeys()->count();

        $userActivatedProjectKeys = $user->getActivatedProjectKeys($project);

        $userHasCheatForProject = $user->hasAvailableCheatForProject($project);

        // todo uncomment
//        if ($userActivatedProjectKeys->count() > 2 && ($userActivatedProjectKeys->count() < $projectKeysCount - 1) && !$userHasCheatForProject) {
        if (true) {
            $user->addCheatForProject($project);
            return;
        }

        if (($userActivatedProjectKeys->count() >= $projectKeysCount - 1) && $userHasCheatForProject) {
            $user->removeCheatForProject($project);
        }
    }
}
