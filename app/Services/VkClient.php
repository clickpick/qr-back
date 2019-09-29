<?php

namespace App\Services;

use App\Jobs\DisableNotificationForVkUser;
use Illuminate\Support\Collection;
use VK\Client\VKApiClient;

class VkClient {
    protected $client;
    private $accessToken;

    private const API_VERSION = '5.95';

    public function __construct()
    {
        $this->client = new VKApiClient(self::API_VERSION);
        $this->accessToken = config('services.vk.app.service');
    }

    public function getUsers($ids, array $fields) {

        $isFew = is_array($ids);

        $response = $this->client->users()->get($this->accessToken, [
            'user_ids' => $isFew ? $ids : [$ids],
            'fields' => $fields,
        ]);

        return $isFew ? $response : $response[0];
    }

    public function sendPushes(Collection $ids, $message) {

        $ids->chunk(100)->each(function(Collection $chunkedIds) use ($message) {

            $result = $this->client->notifications()->sendMessage($this->accessToken, [
                'user_ids' => $chunkedIds->implode(','),
                'message' => $message
            ]);

            collect($result)->filter(function ($item) {
                return !$item['status'];
            })->filter(function( $item) {
                return $item['error']['code'] === 1;
            })->each(function($item) {
                DisableNotificationForVkUser::dispatch($item['user_id']);
            });
        });
    }
}
