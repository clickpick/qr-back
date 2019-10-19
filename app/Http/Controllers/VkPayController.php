<?php

namespace App\Http\Controllers;

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
        return new VkPayParamsResource(
            VkPay::makeOrder(Auth::user(), $request->amount, VkPayOrder::DONATE)
        );
    }

    public function makeCheatOrder() {

        $activeProject = Project::whereIsActive(true)->first();

        if (!Auth::user()->hasAvailableNotFiredCheatForProject($activeProject)) {
            abort(403, 'has no cheats');
        }

        $params = VkPay::makeOrder(Auth::user(), VkPayOrder::CHEAT_VALUE, VkPayOrder::CHEAT);

        $availableCheat = Auth::user()->getAvailableCheatForProject($activeProject);

        $availableCheat->attachVkPayOrderId($params['data']['order_id']);

        return new VkPayParamsResource($params);
    }

    public function notify(VkPayTransactionCallbackRequest $request)
    {
        info(json_encode($request->all()));

        $vkPayResponse = new VkPayResponse($request);

        $orderId = $vkPayResponse->getOrderId();

        $vkPayOrder = VkPayOrder::findOrFail($orderId);
    }
}
