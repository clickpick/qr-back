<?php

namespace App\Console\Commands;

use App\Services\VkClient;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SendPushes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:send_pushes {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pushes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $message = $this->argument('message');

        User::where('notifications_are_enabled', true)->chunk(100, function(Collection $users) use ($message) {
            (new VkClient())->sendPushes($users->pluck('vk_user_id'), $message);
        });


        $this->info('sent');

        return;
    }
}