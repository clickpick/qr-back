<?php

namespace App\Console\Commands;

use App\ProjectKey;
use App\Services\VkClient;
use App\User;
use Illuminate\Console\Command;

class GetFriendsOfRareKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project_key:friends {projectKeyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get friends of project key';

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
     * @return void
     */
    public function handle()
    {
        $projectKeyId = $userId = $this->argument('projectKeyId');
        $projectKey = ProjectKey::find($projectKeyId);

        $users = $projectKey->users;



        $users->each(function (User $user) {

            $friendIds = (new VkClient())->getFriends($user->vk_user_id);
            if (empty($friendIds)) {
                return;
            }

            $registeredFriends = User::whereIn('vk_user_id', $friendIds);

            $this->info($user->id);

            if ($registeredFriends->count() === 0) {
                $this->warn('no registered friends');
            }

            $registeredFriends->each(function (User $friend) {
               $this->line($friend->id);
            });
        });
    }
}
