<?php

use App\DTOs\UserDTO;
use App\Enums\CurrencyTypeEnum;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

test('creates a UserDTO from UserStoreRequest correctly', function () {
    $requestData = [
        'first_name' => 'Kirk',
        'last_name' => 'Lazaris',
        'hourly_rate' => 500,
        'currency' => 'USD',
        'bio' => 'My life',
    ];

    $request = new UserStoreRequest;
    $request->replace($requestData);

    $userDTO = UserDTO::fromRequest($request);

    expect($userDTO)->toBeInstanceOf(UserDTO::class)
        ->and($userDTO->getFirstName())->toEqual('Kirk')
        ->and($userDTO->getLastName())->toEqual('Lazaris')
        ->and($userDTO->getHourlyRate())->toEqual(500)
        ->and($userDTO->getCurrency())->toEqual(CurrencyTypeEnum::USD)
        ->and($userDTO->getBio())->toEqual('My life');
});

test('creates a UserDTO from UserUpdateRequest correctly, using default user values', function () {
    $user = User::factory()->create();

    $requestData = [
        'first_name' => 'Kirk',
        'last_name' => 'Lazaris',
        'hourly_rate' => 600,
        'currency' => 'EUR',
        'bio' => 'My life',
    ];

    $request = new UserUpdateRequest;
    $request->replace($requestData);

    $userDTO = UserDTO::updateFromRequest($user, $request);

    expect($userDTO)->toBeInstanceOf(UserDTO::class)
        ->and($userDTO->getFirstName())->toEqual('Kirk')
        ->and($userDTO->getLastName())->toEqual('Lazaris')
        ->and($userDTO->getHourlyRate())->toEqual(600)
        ->and($userDTO->getCurrency())->toEqual(CurrencyTypeEnum::EUR)
        ->and($userDTO->getBio())->toEqual('My life');
});

test('creates a UserDTO from UserUpdateRequest correctly, using existing user values when not provided', function () {
    $user = User::factory()->create([
        'first_name' => 'Kirk',
        'last_name' => 'Lazaris',
        'hourly_rate' => 400,
        'currency' => CurrencyTypeEnum::GBP,
        'bio' => 'Old bio.',
    ]);

    $requestData = [
        'first_name' => 'Tugg',
        'last_name' => 'Speedman',
        // Omit other fields
    ];

    $request = new UserUpdateRequest;
    $request->replace($requestData);

    $userDTO = UserDTO::updateFromRequest($user, $request);

    expect($userDTO)->toBeInstanceOf(UserDTO::class)
        ->and($userDTO->getFirstName())->toEqual('Tugg')
        ->and($userDTO->getLastName())->toEqual('Speedman')
        ->and($userDTO->getHourlyRate())->toEqual(400)
        ->and($userDTO->getCurrency())->toEqual(CurrencyTypeEnum::GBP)
        ->and($userDTO->getBio())->toEqual('Old bio.');
});

test('converts a UserDTO to an array correctly', function () {
    $userDTO = new UserDTO(
        'Kirk',
        'Lazaris',
        500,
        CurrencyTypeEnum::USD,
        'My life'
    );

    $result = $userDTO->toArray();

    expect($result)->toEqual([
        'first_name' => 'Kirk',
        'last_name' => 'Lazaris',
        'hourly_rate' => 500,
        'currency' => CurrencyTypeEnum::USD,
        'bio' => 'My life',
    ]);
});
