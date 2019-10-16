<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderVkPayRequest;
use App\Services\Facades\VkPay;
use Illuminate\Support\Facades\Auth;

class VkPayController extends Controller
{
    public function makeOrder(OrderVkPayRequest $request) {
        return VkPay::makeOrder(Auth::user(), $request->amount);
    }
}
