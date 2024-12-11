<?php

namespace App\Providers;

use App\Interfaces\CurrencyConverterInterface;
use App\Services\CurrencyConverter\ExchangeRatesApiCurrencyConverter;
use App\Services\CurrencyConverter\LocalCurrencyConverter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CurrencyConverterInterface::class, function ($app) {
            return match (config('currency.converter_driver')) {
                'local' => $app->make(LocalCurrencyConverter::class),
                'api' => $app->make(ExchangeRatesApiCurrencyConverter::class),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
