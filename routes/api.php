<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->prefix("auth")->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('passwordLogin', [AuthController::class,'loginWithPassword']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('forgot-password', [AuthController::class,'forgotPassword']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
    Route::get('/{id}', [ProfileController::class, 'show']);
    Route::post('/{id}/info', [ProfileController::class, 'updateInfo']);
    Route::post('/{id}/password', [ProfileController::class, 'updatePassword']);
    Route::post('/{id}/address', [ProfileController::class, 'updateAddress']);
});

// Admin
Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    // Chat, Admin edition
    Route::get('admin/inbox', [ChatController::class, 'getInbox']);
    Route::get('admin/inbox/{id}', [ChatController::class, 'getAdminMessages']);
    Route::post('admin/inbox/{id}', [ChatController::class, 'replyMessage']);

    Route::apiResource('admin', UserController::class);
});

// Chat
Route::get('chat', [ChatController::class, 'getUserChat']);
Route::post('chat', [ChatController::class, 'sendMessage']);
Route::get('notif', [ChatController::class, 'notification']);


