<?php

use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Pages

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/passwordLogin', function () {
    return view('auth.loginWithPass');
})->name('passwordLogin');

Route::get('/verify-otp', function () {
    return view('auth.otp');
})->name('verify.otp');


// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Reset Password

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetUrl'])->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password',[ResetPasswordController::class, 'resetPassword'] )->name('password.update');
