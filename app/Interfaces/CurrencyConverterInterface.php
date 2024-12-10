<?php

namespace App\Interfaces;

use App\Enums\CurrencyTypeEnum;
use App\Exceptions\ConversionException;

interface CurrencyConverterInterface
{
    /**
     * Convert an amount from one currency to another.
     *
     * @param  int  $amount  The amount in the source currency's smallest unit (e.g., cents for USD).
     * @param  CurrencyTypeEnum  $fromCurrency  The source currency.
     * @param  CurrencyTypeEnum  $toCurrency  The target currency.
     * @return int The converted amount in the target currency's smallest unit.
     *
     * @throws ConversionException If the conversion cannot be performed.
     */
    public function convert(int $amount, CurrencyTypeEnum $fromCurrency, CurrencyTypeEnum $toCurrency): int;
}
