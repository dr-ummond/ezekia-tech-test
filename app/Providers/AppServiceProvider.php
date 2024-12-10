<?php

namespace App\Providers;

use App\Interfaces\CurrencyConverterInterface;
use App\Services\ApiCurrencyConverter;
use App\Services\LocalCurrencyConverter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CurrencyConverterInterface::class,
            match (config('currency.converter_driver')) {
                'local' => LocalCurrencyConverter::class,
                'api' => ApiCurrencyConverter::class
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
