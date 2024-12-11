<?php

use App\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    config([
        'currency.rates' => [
            'USD' => ['GBP' => 0.7],
        ],
        'currency.converter_driver' => 'local',
    ]);
});

test('a user is returned even when the currency is not sent in the request', function () {
    $user = User::factory()
        ->state([
            'hourly_rate' => 1500,
            'currency' => 'USD',
        ])
        ->create();

    $response = $this->getJson(route('user', [$user->uuid]))
        ->assertSuccessful()
        ->json();

    expect($response['data']['is_converted'])->toBeFalse();
});

test('conversion data is included in the resource when currency is provided', function () {

    $user = User::factory()
        ->state([
            'hourly_rate' => 1000,
            'currency' => 'USD',
        ])
        ->create();

    $response = $this->getJson(route('user', [$user->uuid, 'currency' => 'GBP']))
        ->assertSuccessful()
        ->json();

    $data = $response['data'];

    expect($data['is_converted'])->toBeTrue()
        ->and($data['hourly_rate'])->toBe(700)
        ->and($data['currency'])->toBe('GBP');
});

test('the request supports lowercase currency query param value', function () {

    $user = User::factory()
        ->state([
            'hourly_rate' => 500,
            'currency' => 'USD',
        ])
        ->create();

    $response = $this->getJson(route('user', [$user->uuid, 'currency' => 'gbp']))
        ->assertSuccessful()
        ->json();

    $data = $response['data'];

    expect($data['is_converted'])->toBeTrue()
        ->and($data['hourly_rate'])->toBe(350)
        ->and($data['currency'])->toBe('GBP');
});

test('returns 400 when uuid is invalid', function () {
    $this->getJson(route('user', 'gibberish'))->assertStatus(400);
});

test('returns 404 when user does not exist', function () {
    $this->getJson(route('user', (string) Str::uuid()))->assertStatus(404);
});
