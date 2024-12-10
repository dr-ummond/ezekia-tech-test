<?php

use App\Models\User;
use Illuminate\Support\Str;

test('a user can be updated', function () {
    $user = User::factory()
        ->state([
            'hourly_rate' => 2500,
            'currency' => 'EUR',
        ])
        ->create();

    $data = [
        'first_name' => 'Tugg',
        'last_name' => 'Speedman',
        'hourly_rate' => 3000,
        'currency' => 'GBP',
        'bio' => 'Updated bio',
    ];

    $this->putJson(route('user.update', $user->uuid), $data)->assertSuccessful();

    $user->refresh();

    expect($user->first_name)->toBe($data['first_name'])
        ->and($user->last_name)->toBe($data['last_name'])
        ->and($user->hourly_rate)->toBe($data['hourly_rate'])
        ->and($user->currency->value)->toBe($data['currency'])
        ->and($user->bio)->toBe($data['bio']);
});

test('a user can be updated with default values from the model if not provided in the request', function () {
    $user = User::factory()
        ->state([
            'hourly_rate' => 2500,
            'currency' => 'EUR',
            'bio' => 'Original bio',
        ])
        ->create();

    $data = [
        'first_name' => 'Tugg',
        'last_name' => 'Speedman',
    ];

    $this->putJson(route('user.update', $user->uuid), $data)
        ->assertSuccessful();

    $user->refresh();

    expect($user->first_name)->toBe($data['first_name'])
        ->and($user->last_name)->toBe($data['last_name'])
        // Ensure that the fields not provided in the request default to the current model values
        ->and($user->hourly_rate)->toBe(2500)
        ->and($user->currency->value)->toBe('EUR')
        ->and($user->bio)->toBe('Original bio');
});

test('a user update request is validated correctly', function () {
    $user = User::factory()->create();

    $data = [
        'first_name' => '',
        'hourly_rate' => -100,
        'currency' => 'BTC',
    ];

    $this->putJson(route('user.update', $user->uuid), $data)
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'first_name',
            'hourly_rate',
            'currency',
        ]);
});

test('bio can be updated or omitted', function () {
    $user = User::factory()->create([
        'bio' => 'Original bio',
    ]);

    $this->putJson(route('user.update', $user->uuid), [])
        ->assertSuccessful();

    $user->refresh();

    expect($user->bio)->toBe('Original bio');

    // bio can be set to null
    $this->putJson(route('user.update', $user->uuid), ['bio' => null])
        ->assertSuccessful();

    $user->refresh();

    expect($user->bio)->toBeNull();
});

test('returns 400 when uuid is invalid', function () {
    $this->putJson(route('user.update', 'gibberish'))->assertStatus(400);
});

test('returns 404 when user does not exist', function () {
    $this->putJson(route('user.update', (string) Str::uuid()))->assertStatus(404);
});
