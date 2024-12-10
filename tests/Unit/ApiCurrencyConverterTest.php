<?php

use App\Enums\CurrencyTypeEnum;
use App\Exceptions\ConversionException;
use App\Services\ApiCurrencyConverter;
use Illuminate\Support\Facades\Http;

test('returns the same amount if from and to currency are the same', function () {
    $converter = new ApiCurrencyConverter;

    $amount = 1000;
    $currency = CurrencyTypeEnum::USD;

    $result = $converter->convert($amount, $currency, $currency);

    expect($result)->toEqual($amount);
});

test('correctly converts the amount when a valid rate is available', function () {

    Http::fake([
        '*' => Http::response([
            'rates' => [
                'EUR' => 0.85, // 1 USD = 0.85 EUR
            ],
        ], 200),
    ]);

    $converter = new ApiCurrencyConverter;

    $amount = 1000; // 1000 cents (10 USD)
    $fromCurrency = CurrencyTypeEnum::USD;
    $toCurrency = CurrencyTypeEnum::EUR;

    $result = $converter->convert($amount, $fromCurrency, $toCurrency);

    expect($result)->toEqual(850); // 1000 * 0.85 = 850 (cents)
});

test('throws a ConversionException if the API request fails or returns invalid data', function () {

    Http::fake([
        '*' => Http::response([], 500),
    ]);

    $converter = new ApiCurrencyConverter;

    $amount = 1000; // 1000 cents (10 USD)
    $fromCurrency = CurrencyTypeEnum::USD;
    $toCurrency = CurrencyTypeEnum::EUR;

    $converter->convert($amount, $fromCurrency, $toCurrency);
})->throws(ConversionException::class, 'Unable to fetch conversion rate from USD to EUR.');

test('throws a ConversionException if the exchange rate is not available', function () {
    Http::fake([
        '*' => Http::response([
            'rates' => [], // No exchange rates returned
        ], 200),
    ]);

    $converter = new ApiCurrencyConverter;

    $amount = 1000; // 1000 cents (10 USD)
    $fromCurrency = CurrencyTypeEnum::USD;
    $toCurrency = CurrencyTypeEnum::GBP; // GBP rate is missing

    $converter->convert($amount, $fromCurrency, $toCurrency);
})->throws(ConversionException::class, 'Unable to fetch conversion rate from USD to GBP.');
