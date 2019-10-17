<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderVkPayRequest;
use App\Http\Resources\VkPayParamsResource;
use App\Services\Facades\VkPay;
use Illuminate\Support\Facades\Auth;

class VkPayController extends Controller
{
    public function makeOrder(OrderVkPayRequest $request) {
        return new VkPayParamsResource(VkPay::makeOrder(Auth::user(), $request->amount));
    }
}
