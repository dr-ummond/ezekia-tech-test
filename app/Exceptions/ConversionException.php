<?php

namespace App\Exceptions;

use Exception;

class ConversionException extends Exception
{
    public static function unsupportedConversion(string $fromCurrency, string $toCurrency): self
    {
        return new self("Conversion rate not supported from {$fromCurrency} to {$toCurrency}.");
    }

    public static function apiError(string $fromCurrency, string $toCurrency): self
    {
        return new self("Unable to fetch conversion rate from {$fromCurrency} to {$toCurrency}.");
    }
}
