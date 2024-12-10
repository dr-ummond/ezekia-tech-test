<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use App\Enums\CurrencyTypeEnum;
use App\Http\Requests\UserShowRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\CurrencyConverterInterface;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        protected UserService $user_service,
        protected CurrencyConverterInterface $currency_converter
    ) {}

    public function show(User $user, UserShowRequest $request): UserResource
    {
        $currency = $request->query('currency');

        // If the currency is the same as the user's or if currency is not provided, no conversion is required
        if ($currency === $user->currency->value || ! $currency) {
            return new UserResource($user);
        }

        $convertedRate = $this->currency_converter->convert(
            $user->hourly_rate,
            CurrencyTypeEnum::from($user->currency->value),
            CurrencyTypeEnum::from($currency)
        );

        $user->converted_rate = $convertedRate;
        $user->converted_currency = $currency;

        return new UserResource($user);
    }

    public function store(UserStoreRequest $request): UserResource
    {
        $userDto = UserDTO::fromRequest($request);

        $user = $this->user_service->createUser($userDto);

        return new UserResource($user);
    }

    public function update(User $user, UserUpdateRequest $request): UserResource
    {
        $userDto = UserDTO::updateFromRequest($user, $request);

        $this->user_service->updateUser($user, $userDto);

        return new UserResource($user);
    }

    public function destroy(User $user): Response
    {
        $this->user_service->deleteUser($user);

        return response()->noContent();
    }
}
