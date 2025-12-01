<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\DrHopeController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\WorkshopController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SubscriptionController;

// Auth routes
Route::get('/countries'    , [Controller::class, 'countries']);
Route::post('/register'    , [AuthController::class, 'register']);
Route::post('/login'       , [AuthController::class, 'login']);
Route::post('/google-login', [AuthController::class, 'loginWithGoogle']);

// Workshop routes
Route::prefix('workshops')->group(function () {
    Route::get('/'    , [WorkshopController::class, 'index']);
    Route::get('/{id}', [WorkshopController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Home routes
    Route::prefix('home')->group(function () {
        Route::get('/settings', [HomeController::class, 'settings']);
    });
    
    // DrHope routes
    Route::prefix('drhope')->group(function () {
        Route::get('/products'       , [DrHopeController::class, 'products']);
        Route::get('/videos'         , [DrHopeController::class, 'videos']);
        Route::get('/gallery'        , [DrHopeController::class, 'gallery']);
        Route::get('/instagram-lives', [DrHopeController::class, 'instagramLives']);
        Route::get('/partners'       , [DrHopeController::class, 'partners']);
        Route::get('/partners/{id}'  , [DrHopeController::class, 'partnerDetails']);
        Route::post('/support'       , [DrHopeController::class, 'support']);
    });

    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::post('/add'             , [CartController::class, 'addToCart']);
        Route::put('/update'           , [CartController::class, 'updateCart']);
        Route::get('/summary'          , [CartController::class, 'getCartSummary']);
        Route::delete('/delete-item'   , [CartController::class, 'deleteCartItem']);
    });

    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/summary'          , [OrderController::class, 'orderSummary']);
        Route::post('/create'          , [OrderController::class, 'store']);
    });

    // Subscription routes
    Route::prefix('subscriptions')->group(function () {
        Route::post('/create'          , [SubscriptionController::class, 'create']);
        Route::post('/process-payment' , [SubscriptionController::class, 'processPayment']);
    });

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/details'          , [ProfileController::class, 'getProfileDetails']);
        Route::get('/suggest-workshops', [ProfileController::class, 'suggestWorkshops']);
    });
});

// Payment callback routes (public, no auth required)
Route::post('/foloosi/callback', [PaymentController::class, 'foloosiWebhook'])->name('foloosi.callback');
Route::post('/foloosi/verify', [PaymentController::class, 'verifyPayment'])->name('foloosi.verify');