<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\Referral\ReferralPendingController;
use App\Http\Controllers\Referral\Auth\ReferralAuthController;
use App\Http\Controllers\Referral\ReferralDashboardController;
use App\Http\Controllers\Referral\Auth\ReferralRegisterController;

Route::middleware('web') // <-- THIS ONE LINE FIXES EVERYTHING
    ->prefix('referral')
    ->name('referral.')
    ->group(function () {
        /**
         * Registration
         */
        Route::get('/create', [ReferralAuthController::class, 'create'])->name('create');
        Route::post('/store', [ReferralAuthController::class, 'store'])->name('register');

        /**
         * Authentication
         */
        Route::get('/login', [ReferralAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [ReferralAuthController::class, 'login'])->name('login.submit');
        Route::post('/logout', [ReferralAuthController::class, 'logout'])->name('logout');

        /**
         * Forgot / Reset Password
         */
        Route::get('/forgot-password', [ReferralAuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
        Route::post('/forgot-password', [ReferralAuthController::class, 'sendResetLink'])->name('forgot-password.submit');
        Route::get('/reset-password/{token}', [ReferralAuthController::class, 'showResetForm'])->name('reset-password');
        Route::post('/reset-password', [ReferralAuthController::class, 'resetPassword'])->name('reset-password.submit');


        

        /**
         * Dashboard & Profile
         */
        Route::get('/dashboard', [ReferralDashboardController::class, 'index'])->name('dashboard');
        Route::post('/{id}/update-profile', [ReferralDashboardController::class, 'updateProfile'])->name('updateProfile');
    });
    Route::middleware('web') 
    ->prefix('referral')
    ->name('referral.')
    ->group(function () {
    Route::get('/pending', [ReferralPendingController::class, 'index'])->name('pending');
    });