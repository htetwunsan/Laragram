<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::prefix('auth')->group(function () {

    Route::get('/signup', [RegisteredUserController::class, 'create'])
        ->middleware('guest')
        ->name('signup');

    Route::post('/signup', [RegisteredUserController::class, 'store'])
        ->middleware('guest');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->middleware('guest')
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->middleware('guest')
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest')
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('guest')
        ->name('password.update');

    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->middleware('auth')
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['auth', 'throttle:6,1'])
        ->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->middleware('auth')
        ->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware('auth');

    Route::get('/change-password', [ChangePasswordController::class, 'edit'])
        ->middleware('auth')
        ->name('password.change');

    Route::match(['post', 'put', 'patch'], '/change-password', [ChangePasswordController::class, 'update'])
        ->middleware('auth');

    Route::match(['get', 'post'], '/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

    Route::get('/edit', [AuthenticatedUserController::class, 'edit'])
        ->middleware('auth')
        ->name('auth.edit');

    Route::put('/edit', [AuthenticatedUserController::class, 'update'])
        ->middleware('auth');

    Route::patch('/profile-image', [AuthenticatedUserController::class, 'updateProfileImage'])
        ->middleware('auth')
        ->name('auth.update.profile-image');

    Route::delete('/profile-image', [AuthenticatedUserController::class, 'destroyProfileImage'])
        ->middleware('auth')
        ->name('auth.destroy.profile-image');
});

