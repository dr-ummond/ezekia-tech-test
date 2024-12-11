<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use App\Enums\CurrencyTypeEnum;
use App\Http\Requests\UserShowRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        protected UserService $user_service,
    ) {}

    public function show(User $user, UserShowRequest $request): UserResource
    {
        $currency = $request->input('currency');

        // If the currency is the same as the user's or if currency is not provided, no conversion is required
        if ($currency === $user->currency->value || ! $currency) {
            return new UserResource($user);
        }

        $convertedUser = $this->user_service->convertUserCurrency(
            $user,
            CurrencyTypeEnum::from($currency)
        );

        return new UserResource($convertedUser);
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
