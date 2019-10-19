<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachTransactionIdRequest;
use App\Http\Requests\OrderVkPayRequest;
use App\Http\Requests\VkPayTransactionCallbackRequest;
use App\Http\Resources\VkPayOrderResource;
use App\Http\Resources\VkPayParamsResource;
use App\Services\Facades\VkPay;
use App\Services\VkPayResponse;
use App\VkPayOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VkPayController extends Controller
{
    public function makeOrder(OrderVkPayRequest $request)
    {
        return new VkPayParamsResource(
            VkPay::makeOrder(Auth::user(), $request->amount)
        );
    }

    public function notify(VkPayTransactionCallbackRequest $request)
    {

        info(json_encode($request->all()));

        $vkPayResponse = new VkPayResponse($request);

        $orderId = $vkPayResponse->getOrderId();

        $vkPayOrder = VkPayOrder::findOrFail($orderId);
    }
}
