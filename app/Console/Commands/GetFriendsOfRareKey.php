<?php

namespace App\Console\Commands;

use App\ProjectKey;
use App\Services\VkClient;
use App\User;
use Illuminate\Console\Command;
use VK\Exceptions\Api\VKApiPrivateProfileException;

class GetFriendsOfRareKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project_key:friends {projectKeyId} {--notify}';

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

            $this->info($user->id);

            try {
                $friendIds = (new VkClient())->getFriends($user->vk_user_id);
            } catch (VKApiPrivateProfileException $e) {
                $this->warn('private profile');
                return;
            } catch (\Exception $e) {
                $this->warn('api error');
                return;
            }

            if (empty($friendIds)) {
                return;
            }

            $registeredFriends = User::whereIn('vk_user_id', $friendIds);

            if ($registeredFriends->count() === 0) {
                $this->warn('no registered friends');
            }

            $registeredFriends->each(function (User $friend) {
                $this->warn($friend->id);

                if ($this->option('notify')) {
                    if (!$friend->notifications_are_enabled) {
                        $this->line('notifications are disabled');
                        return;
                    }

                    try {
                        $friend->sendPush('По секрету, у твоего друга есть ТОТ САМЫЙ код! Поторопись, ты еще можешь выиграть приз!');
                    } catch (\Exception $e) {
                        $this->line('api push error');
                        return;
                    }
                    $this->line('notified');
                }
            });
        });
    }
}
