<?php

namespace App\Services;

use App\Http\Requests\VkPayTransactionCallbackRequest;

class VkPayResponse
{

    private $version;
    private $data;
    private $signature;

    public function __construct(VkPayTransactionCallbackRequest $request)
    {
        $this->version = $request->version;
        $this->data = $request->data;
        $this->signature = $request->signature;

        if (!$this->verifySignature()) {
            throw new \Exception('bad request');
        }
    }


    private function verifySignature()
    {
        return openssl_verify($this->data, base64_decode($this->signature), file_get_contents(storage_path('app/vkpay.pem')));
    }

    public function getDecodedData()
    {
        return json_decode(base64_decode($this->data), true);
    }

    public function getOrderId() {
        return $this->getDecodedData()['body']['merchant_param']['order_id'];
    }
}
