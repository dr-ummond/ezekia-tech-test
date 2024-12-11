<?php

namespace App\Services\CurrencyConverter;

use App\Enums\CurrencyTypeEnum;
use App\Exceptions\ConversionException;
use App\Interfaces\CurrencyConverterInterface;

class LocalCurrencyConverterService implements CurrencyConverterInterface
{
    /**
     * @throws ConversionException
     */
    public function convert(int $amount, CurrencyTypeEnum $fromCurrency, CurrencyTypeEnum $toCurrency): int
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rates = config('currency.rates');

        if (! isset($rates[$fromCurrency->value][$toCurrency->value])) {
            throw ConversionException::unsupportedConversion($fromCurrency->value, $toCurrency->value);
        }

        // Get the exchange rate for the conversion
        $rate = $rates[$fromCurrency->value][$toCurrency->value];

        // Multiply amount by rate, and round to ensure integer precision
        return (int) round($amount * $rate);
    }
}
