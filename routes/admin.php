<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('/admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)
        ->name('dashboard');

    Route::get('/users/{user}/edit/password', [UserController::class, 'editPassword'])
        ->name('users.edit-password');

    Route::patch('/users/{user}/password', [UserController::class, 'updatePassword'])
        ->name('users.update-password');

    Route::resources([
        'users' => UserController::class,
        'posts' => PostController::class
    ]);
});
