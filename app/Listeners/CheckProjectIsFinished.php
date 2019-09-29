<?php

namespace App\Listeners;

use App\Events\ProjectKeyActivated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckProjectIsFinished
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
        $projectKey = $event->projectKey;
        $project = $projectKey->project;

        $project->checkIsFinished();
    }
}
