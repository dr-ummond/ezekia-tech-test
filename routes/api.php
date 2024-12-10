<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::get('/{user}', [UserController::class, 'show'])
        ->name('user');

    Route::post('/', [UserController::class, 'store'])
        ->name('user.store');

    Route::put('/{user}', [UserController::class, 'update'])
        ->name('user.update');

    Route::delete('/{user}', [UserController::class, 'destroy'])
        ->name('user.destroy');
});
