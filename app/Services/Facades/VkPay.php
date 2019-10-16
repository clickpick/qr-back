<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class VkPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vkPay';
    }
}
