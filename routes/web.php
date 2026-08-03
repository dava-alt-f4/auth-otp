<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/verify-otp', function () {
    return view('auth.otp');
})->name('verify.otp');



Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

