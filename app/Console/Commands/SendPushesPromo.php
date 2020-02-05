<?php

namespace App\Console\Commands;

use App\Services\VkClient;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SendPushesPromo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:send_pushes_promo {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pushes promo';

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
     * @throws \Exception
     */
    public function handle()
    {
        $userId = $this->option('id');

        if ($userId) {
            $user = User::findOrFail($userId);

            $n = random_int(1, 6);

            $message = "У {$n} твоих друзей уже есть открытые символы. Поторопись, если хочешь выиграть!";

            (new VkClient())->sendPushes(collect([$user->vk_user_id]), $message);
        } else {

            $count = User::where('notifications_are_enabled', true)->count();

            $bar = $this->output->createProgressBar($count);
            $bar->start();

            User::where('notifications_are_enabled', true)->chunk(100, function (Collection $users) use ($bar) {

                $n = random_int(1, 6);

                $message = "У {$n} твоих друзей уже есть открытые символы. Поторопись, если хочешь выиграть!";

                try {
                    (new VkClient())->sendPushes($users->pluck('vk_user_id'), $message);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }

                $bar->advance($users->count());
            });
        }


        $this->line(PHP_EOL);
        $this->info('sent');

        return;
    }
}
