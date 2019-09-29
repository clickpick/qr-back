<?php

namespace App\Listeners;

use App\Events\ProjectHasFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendProjectFinishedNotifications implements ShouldQueue
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
     * @param ProjectHasFinished $event
     * @return void
     */
    public function handle(ProjectHasFinished $event)
    {
        $project = $event->project;
        $project->sendFinishedNotification();
    }
}
