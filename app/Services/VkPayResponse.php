<?php

namespace App\Services;

use App\Exceptions\InvalidSignatureException;
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
            throw new InvalidSignatureException('bad sign');
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

    public function getOrderId()
    {
        return $this->getBody()['merchant_param']['order_id'];
    }

    public function getStatus()
    {
        return $this->getBody()['status'];
    }

    public function getHeader()
    {
        return $this->getDecodedData()['header'];
    }

    public function getBody()
    {
        return $this->getDecodedData()['body'];
    }

    public function getVersion()
    {
        return $this->version;
    }


    public function createSuccessResponseData()
    {
        return [
            'header' => [
                'status' => 'OK',
                'ts' => now(),
                'client_id' => config('services.vk.pay.merchant_id')
            ],
            'body' => [
                'transaction_id' => $this->getBody()['transaction_id'],
                'notify_type' => $this->getBody()['notify_type'],
            ]
        ];
    }

    public function createErrorResponseData($errorMessage)
    {
        return [
            'header' => [
                'status' => 'ERROR',
                'ts' => now(),
                'client_id' => config('services.vk.pay.merchant_id')
            ],
            'body' => [
                'transaction_id' => $this->getBody()['transaction_id'],
                'notify_type' => $this->getBody()['notify_type'],
                'error' => [
                    'code' => $errorMessage,
                    'message' => 'Error message'
                ]
            ]
        ];
    }


    public function successResponse()
    {

        $data = $this->createSuccessResponseData();

        $base64Data = base64_encode(json_encode($data));

        $sign = sha1($base64Data . config('services.vk.pay.secret'));


        return [
            'version' => $this->getVersion(),
            'data' => $base64Data,
            'signature' => $sign
        ];
    }

    public function errorResponse($errorMessage)
    {
        $data = $this->createErrorResponseData($errorMessage);

        $base64Data = base64_encode(json_encode($data));

        $sign = sha1($base64Data . config('services.vk.pay.secret'));


        return [
            'version' => $this->getVersion(),
            'data' => $base64Data,
            'signature' => $sign
        ];
    }
}
