<?php

namespace App\DTOs;

use App\Enums\CurrencyTypeEnum;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

class UserDTO
{
    public User $user;

    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly float $hourlyRate,
        private readonly ?CurrencyTypeEnum $currency,
        private readonly ?string $bio
    ) {}

    public static function fromRequest(UserStoreRequest $request): self
    {
        return new self(
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('hourly_rate'),
            CurrencyTypeEnum::tryFrom($request->input('currency', CurrencyTypeEnum::USD->value)),
            $request->input('bio')
        );
    }

    public static function updateFromRequest(User $user, UserUpdateRequest $request): self
    {
        return new self(
            $request->input('first_name', $user->first_name),
            $request->input('last_name', $user->last_name),
            $request->input('hourly_rate', $user->hourly_rate),
            CurrencyTypeEnum::tryFrom($request->input('currency', $user->currency->value)),
            $request->input('bio', $user->bio)
        );
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'hourly_rate' => $this->hourlyRate,
            'currency' => $this->currency,
            'bio' => $this->bio,
        ];
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getHourlyRate(): float
    {
        return $this->hourlyRate;
    }

    public function getCurrency(): CurrencyTypeEnum
    {
        return $this->currency;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }
}
