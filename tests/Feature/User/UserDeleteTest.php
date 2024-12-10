<?php

use App\Models\User;
use Illuminate\Support\Str;

test('a user can be deleted', function () {
    $user = User::factory()->create();

    $this->deleteJson(route('user.destroy', $user->uuid))->assertSuccessful();

    $this->assertNotNull($user->refresh()->deleted_at);
});

test('returns 400 when uuid is invalid', function () {
    $this->deleteJson(route('user.destroy', 'gibberish'))->assertStatus(400);
});

test('returns 404 when user does not exist', function () {
    $this->deleteJson(route('user.destroy', (string) Str::uuid()))->assertStatus(404);
});
