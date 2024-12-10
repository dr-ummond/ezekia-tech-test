<?php

use App\Models\User;

test('a user can be created', function () {

    $data = [
        'first_name' => 'Kirk',
        'last_name' => 'Lazaris',
        'hourly_rate' => 2500,
        'currency' => 'GBP',
        'bio' => 'My life',
    ];

    $response = $this->postJson(route('user.store', $data))
        ->assertSuccessful()
        ->json();

    $user = User::findByUuid($response['data']['uuid']);

    expect($user->first_name)->toBe($data['first_name'])
        ->and($user->last_name)->toBe($data['last_name'])
        ->and($user->hourly_rate)->toBe($data['hourly_rate'])
        ->and($user->currency->value)->toBe($data['currency'])
        ->and($user->bio)->toBe($data['bio']);
});

test('a user cannot be created with invalid currency', function () {

    $data = [
        'first_name' => 'Kirk',
        'last_name' => 'Lazaris',
        'hourly_rate' => 2500,
        'bio' => 'My life',
    ];

    $this->postJson(route('user.store', $data))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['currency']);

    $data['currency'] = 'BTC';

    $this->postJson(route('user.store', $data))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['currency']);
});

test('a user cannot be created with invalid hourly rate', function () {

    $data = [
        'first_name' => 'Kirk',
        'last_name' => 'Lazaris',
        'currency' => 'GBP',
        'bio' => 'My life',
    ];

    $this->postJson(route('user.store', $data))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['hourly_rate']);

    $data['hourly_rate'] = 'not a number';

    $this->postJson(route('user.store', $data))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['hourly_rate']);
});

test('a user can be created without a bio', function () {

    $data = [
        'first_name' => 'Kirk',
        'last_name' => 'Lazaris',
        'hourly_rate' => 2500,
        'currency' => 'GBP',
    ];

    $response = $this->postJson(route('user.store', $data))
        ->assertSuccessful()
        ->json();

    $data = $response['data'];

    $this->assertDatabaseHas('users', [
        'uuid' => $data['uuid'],
        'bio' => null,
    ]);
});

test('lowercase currency is handled in the request', function () {

    $data = [
        'first_name' => 'Kirk',
        'last_name' => 'Lazaris',
        'hourly_rate' => 2500,
        'currency' => 'gbp',
        'bio' => 'My life',
    ];

    $response = $this->postJson(route('user.store', $data))
        ->assertSuccessful()
        ->json();

    $data = $response['data'];

    $this->assertDatabaseHas('users', [
        'uuid' => $data['uuid'],
        'currency' => strtoupper($data['currency']),
    ]);
});
