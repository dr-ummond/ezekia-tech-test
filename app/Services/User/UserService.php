<?php

namespace App\Services\User;

use App\DTOs\UserDTO;
use App\Enums\CurrencyTypeEnum;
use App\Interfaces\CurrencyConverterInterface;
use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $user_repo,
        private readonly CurrencyConverterInterface $currency_converter
    ) {}

    public function createUser(UserDTO $userDTO): User
    {
        return $this->user_repo->create($userDTO);
    }

    public function updateUser(User $user, UserDTO $userDTO): void
    {
        $this->user_repo->update($user, $userDTO);
    }

    public function deleteUser(User $user): void
    {
        $this->user_repo->delete($user);
    }

    public function convertUserCurrency(User $user, CurrencyTypeEnum $currency): User
    {
        $convertedRate = $this->currency_converter->convert(
            $user->hourly_rate,
            CurrencyTypeEnum::from($user->currency->value),
            $currency
        );

        $user->converted_rate = $convertedRate;
        $user->converted_currency = $currency;

        return $user;
    }
}
