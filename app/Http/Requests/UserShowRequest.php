<?php

namespace App\Http\Requests;

use App\Enums\CurrencyTypeEnum;
use App\Http\Requests\Traits\UppercaseCurrency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UserShowRequest extends FormRequest
{
    use UppercaseCurrency;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currency' => ['nullable', new Enum(CurrencyTypeEnum::class)],
        ];
    }
}
