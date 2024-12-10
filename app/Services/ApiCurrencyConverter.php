<?php

namespace App\Services;

use App\Enums\CurrencyTypeEnum;
use App\Exceptions\ConversionException;
use App\Interfaces\CurrencyConverterInterface;
use Illuminate\Support\Facades\Http;

class ApiCurrencyConverter implements CurrencyConverterInterface
{
    protected string $apiUrl;

    protected string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.exchange_rates.base_url');
        $this->apiKey = config('services.exchange_rates.api_key');
    }

    /**
     * @throws ConversionException
     */
    public function convert(int $amount, CurrencyTypeEnum $fromCurrency, CurrencyTypeEnum $toCurrency): int
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $response = Http::get($this->apiUrl, [
            'access_key' => $this->apiKey,
            'symbols' => $toCurrency->value,
        ]);

        if (! $response->successful() || ! isset($response->json()['rates'][$toCurrency->value])) {
            throw ConversionException::apiError($fromCurrency->value, $toCurrency->value);
        }

        $rate = $response->json()['rates'][$toCurrency->value];

        // Multiply amount by rate, and round to ensure integer precision
        return (int) round($amount * $rate);
    }
}
