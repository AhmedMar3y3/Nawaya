<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;

// Auth routes
Route::post('/register'    , [AuthController::class, 'register']);
Route::post('/login'       , [AuthController::class, 'login']);
Route::post('/google-login', [AuthController::class, 'loginWithGoogle']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
