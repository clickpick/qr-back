<?php

namespace App\Services;

use App\Jobs\DisableNotificationForVkUser;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use VK\Client\VKApiClient;

class VkClient {
    protected $client;
    private $accessToken;

    private const API_VERSION = '5.95';

    public function __construct()
    {
        $this->client = new VKApiClient(self::API_VERSION, 'ru');
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

    /**
     * @param $uploadUrl
     * @param $image
     */
    public function postStory($uploadUrl, Image $image) {

        $client = new Client();

        $fileName = storage_path('app/stories/temp/' . Str::random() . '.jpg');

        $image->save($fileName);

        $client->post($uploadUrl, [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($fileName, 'r')
                ],
            ]
        ]);

        unlink($fileName);
    }

    public function getCityById($cityId) {
        $result = $this->client->database()->getCitiesById($this->accessToken, [
            'city_ids' => $cityId
        ]);

        return $result[0] ?? null;
    }

    public function getFriends($vkUserId) {
        $result = $this->client->friends()->get($this->accessToken, [
            'user_id' => $vkUserId
        ]);

        return $result['items'] ?? [];
    }
}
