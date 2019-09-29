<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DisableNotificationForVkUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $vkUserId;

    /**
     * Create a new job instance.
     *
     * @param $vkUserId
     */
    public function __construct($vkUserId)
    {
        $this->vkUserId = $vkUserId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::getByVkId($this->vkUserId);

        $user->disableNotifications();
    }
}
