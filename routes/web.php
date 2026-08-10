<?php

use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

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

Route::get('/verify-otp/{hash}', function (string $hash) {
    if (!Cache::has('otp_hash_' . $hash)) {
        abort(419);
    }
    return view('auth.otp');
})->name('verify.otp')->where('hash', '[A-Fa-f0-9]{64}');


// Users
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/profile/{id}', function (string $id) {
    return view('profile', ['userId' => $id]);
})->name('profile');

// Admin
Route::prefix('admin')->group(function () {
    Route::get('/', [UserController::class, 'users'])->name('admin.index');
    Route::get('/create', [UserController::class, 'create'])->name('admin.create');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('admin.edit');
});
// Reset Password

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetUrl'])->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password',[ResetPasswordController::class, 'resetPassword'] )->name('password.update');

// Chat
Route::get('/chat', function () {
    return view('chat');
})->name('chat.index');

// Chat, Admin edition
Route::get('/admin/inbox', function () {
    return view('admin.inbox');
})->name('admin.inbox');
