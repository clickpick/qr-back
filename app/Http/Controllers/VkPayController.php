<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidSignatureException;
use App\Http\Requests\OrderVkPayRequest;
use App\Http\Requests\VkPayTransactionCallbackRequest;
use App\Http\Resources\VkPayParamsResource;
use App\Project;
use App\Services\Facades\VkPay;
use App\Services\VkPayResponse;
use App\VkPayOrder;
use Illuminate\Support\Facades\Auth;

class VkPayController extends Controller
{
    public function makeOrder(OrderVkPayRequest $request)
    {

        $activeProject = Project::whereIsActive(true)->firstOrFail();

        return new VkPayParamsResource(
            VkPay::makeOrder(Auth::user(), $request->amount, VkPayOrder::DONATE, 'wwf')
        );
    }

    public function makeCheatOrder() {

        $activeProject = Project::whereIsActive(true)->first();

        if (!Auth::user()->hasAvailableNotFiredCheatForProject($activeProject)) {
            abort(403, 'has no cheats');
        }

        $params = VkPay::makeOrder(Auth::user(), VkPayOrder::CHEAT_VALUE, VkPayOrder::CHEAT, "{$activeProject->name}");

        $availableCheat = Auth::user()->getAvailableCheatForProject($activeProject);

        $availableCheat->attachVkPayOrderId($params['data']['order_id']);

        return new VkPayParamsResource($params);
    }

    public function notify(VkPayTransactionCallbackRequest $request)
    {
        try {
            $vkPayResponse = new VkPayResponse($request);
        } catch (InvalidSignatureException $e) {
            abort(400);
            return false;
        }

        $orderId = $vkPayResponse->getOrderId();

        $vkPayOrder = VkPayOrder::findOrFail($orderId);

        $vkPayOrder->approve($vkPayResponse->getStatus(), $vkPayResponse->getDecodedData());

        return $vkPayResponse->successResponse();
    }
}
