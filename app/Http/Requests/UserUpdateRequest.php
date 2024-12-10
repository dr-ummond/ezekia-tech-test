<?php

namespace App\Http\Requests;

use App\Enums\CurrencyTypeEnum;
use App\Http\Requests\Traits\UppercaseCurrency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UserUpdateRequest extends FormRequest
{
    use UppercaseCurrency;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'currency' => [new Enum(CurrencyTypeEnum::class)],
            'hourly_rate' => ['numeric', 'min:0'],
        ];
    }
}
