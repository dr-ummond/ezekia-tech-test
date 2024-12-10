<?php

namespace App\Http\Requests\Traits;

trait UppercaseCurrency
{
    protected function prepareForValidation(): void
    {
        if ($this->has('currency')) {
            $this->merge([
                'currency' => strtoupper($this->currency),
            ]);
        }
    }
}
