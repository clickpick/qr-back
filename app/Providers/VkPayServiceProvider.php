<?php

namespace App\Providers;

use App\Services\VkPay;
use Illuminate\Support\ServiceProvider;

class VkPayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('vkPay', function ($app)
        {
            return new VkPay(config('services.vk.pay.merchant_id'), config('services.vk.pay.secret'), config('services.vk.app.secret'));
        });
        $this->app->alias('vkPay', VkPay::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
