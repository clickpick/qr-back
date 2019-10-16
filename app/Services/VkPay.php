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

    public function makeOrder(User $user, $amount)
    {
        $this->vkPayOrder = $user->vkPayOrders()->create([
            'amount' => $amount
        ]);

        return $this->createParams();
    }


    private function createParams()
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
            'description' => 'Пожертвование номер ' . $data['order_id'],
            'action' => 'pay-to-service',
            'merchant_id' => $this->merchantId,
        ];

        $params['sign'] = $this->generateSign($params);

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
