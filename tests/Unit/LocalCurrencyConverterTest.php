<?php

use App\Enums\CurrencyTypeEnum;
use App\Exceptions\ConversionException;
use App\Services\CurrencyConverter\LocalCurrencyConverter;

test('returns the same amount if from and to currency are the same', function () {
    $converter = new LocalCurrencyConverter;

    $amount = 1000;
    $currency = CurrencyTypeEnum::USD;

    $result = $converter->convert($amount, $currency, $currency);

    expect($result)->toEqual($amount);
});

test('correctly converts the amount when a valid rate is available', function () {
    config(['currency.rates' => [
        'USD' => ['EUR' => 0.85], // example rate: 1 USD = 0.85 EUR
        'EUR' => ['USD' => 1.18], // example reverse rate: 1 EUR = 1.18 USD
    ]]);

    $converter = new LocalCurrencyConverter;

    // 1000 cents (10 USD) to EUR (should return 850 cents)
    $amount = 1000;
    $fromCurrency = CurrencyTypeEnum::USD;
    $toCurrency = CurrencyTypeEnum::EUR;

    $result = $converter->convert($amount, $fromCurrency, $toCurrency);

    expect($result)->toEqual(850); // 1000 * 0.85 = 850 (cents)
});

test('throws a ConversionException if conversion is unsupported', function () {
    config(['currency.rates' => [
        'USD' => ['EUR' => 0.85],
    ]]);

    $converter = new LocalCurrencyConverter;

    $amount = 1000; // 10 USD in smallest unit
    $fromCurrency = CurrencyTypeEnum::USD;
    $toCurrency = CurrencyTypeEnum::GBP; // GBP is not supported in the rates

    $converter->convert($amount, $fromCurrency, $toCurrency);
})->throws(ConversionException::class, 'Conversion rate not supported from USD to GBP.');
