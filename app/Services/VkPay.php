<?php

namespace App\Services;

use App\User;

class VkPay
{

    private $vkPayOrder;

    private $merchantId;
    private $merchantSecret;
    private $appSecret;

    public function __construct($merchantId, $merchantSecret, $appSecret)
    {
        $this->merchantId = $merchantId;
        $this->merchantSecret = $merchantSecret;
        $this->appSecret = $appSecret;
    }

    public function makeOrder(User $user, $amount, $destination, $description)
    {
        $this->vkPayOrder = $user->vkPayOrders()->create([
            'amount' => $amount,
            'destination' => $destination
        ]);

        return $this->createParams($description);
    }


    private function createParams($description)
    {
        $data = [
            'amount' => $this->vkPayOrder->amount,
            'currency' => 'RUB',
            'order_id' => $this->vkPayOrder->id,
            'ts' => time()
        ];

        $merchant_data = base64_encode(json_encode($data));
        $data['merchant_data'] = $merchant_data;
        $data['merchant_sign'] = sha1($merchant_data . $this->merchantSecret);


        $params = [
            'amount' => $this->vkPayOrder->amount,
            'data' => json_encode($data),
            'description' => $description,
            'action' => 'pay-to-service',
            'merchant_id' => $this->merchantId,
        ];

        $params['sign'] = $this->generateSign($params);

        $params['data'] = json_decode($params['data'], true);

        return $params;
    }

    private function generateSign($params) {
        $sign = '';
        foreach ($params as $key => $value) {
            if ($key != 'action') {
                $sign .= ($key.'='.$value);
            }
        }
        $sign .= $this->appSecret;
        return md5($sign);
    }
}
