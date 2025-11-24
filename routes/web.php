<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SupportMessageController;


// public routes //
Route::get('/'            ,[AuthController::class, 'loadLoginPage'])->name('loginPage');
Route::post('/login-admin',[AuthController::class, 'loginUser'])->name('loginUser');

// protected routes //
Route::middleware(['auth.admin'])->group(function () {
    Route::post('/logout'  ,[AuthController::class, 'logout'])->name('admin.logout'); 
    Route::get('/dashboard',[AuthController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Routes
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/'              , [UserController::class, 'index'])->name('index');
        Route::get('/create'        , [UserController::class, 'create'])->name('create');
        Route::post('/'             , [UserController::class, 'store'])->name('store');
        Route::get('/{user}'        , [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit'   , [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}'        , [UserController::class, 'update'])->name('update');
        Route::delete('/{user}'     , [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle', [UserController::class, 'toggleStatus'])->name('toggle');
        Route::post('/bulk-action'  , [UserController::class, 'bulkAction'])->name('bulk-action');
    });

    // setting routes //
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // Package Routes
    Route::prefix('packages')->name('admin.packages.')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::put('/', [PackageController::class, 'update'])->name('update');
    });

    // Support Messages Routes
    Route::prefix('support-messages')->name('admin.support-messages.')->group(function () {
        Route::get('/'                   , [SupportMessageController::class, 'index'])->name('index');
        Route::get('/{supportMessage}'   , [SupportMessageController::class, 'show'])->name('show');
        Route::delete('/{supportMessage}', [SupportMessageController::class, 'destroy'])->name('destroy');
    });


    // City Routes
    Route::prefix('cities')->name('admin.cities.')->group(function () {
        Route::get('/'              , [CityController::class, 'index'])->name('index');
        Route::post('/'             , [CityController::class, 'store'])->name('store');
        Route::put('/{city}'        , [CityController::class, 'update'])->name('update');
        Route::delete('/{city}'     , [CityController::class, 'destroy'])->name('destroy');
    });

    // School Routes
    Route::prefix('schools')->name('admin.schools.')->group(function () {
        Route::get('/'              , [SchoolController::class, 'index'])->name('index');
        Route::post('/'             , [SchoolController::class, 'store'])->name('store');
        Route::put('/{school}'      , [SchoolController::class, 'update'])->name('update');
        Route::delete('/{school}'   , [SchoolController::class, 'destroy'])->name('destroy');
    });


    // Subscription Routes
    Route::prefix('subscriptions')->name('admin.subscriptions.')->group(function () {
        Route::get('/'                 , [SubscriptionController::class, 'index'])->name('index');
        Route::get('/trials'           , [SubscriptionController::class, 'index'])->name('trials');
        Route::get('/create'           , [SubscriptionController::class, 'create'])->name('create');
        Route::post('/'                , [SubscriptionController::class, 'store'])->name('store');
        Route::get('/{subscription}'   , [SubscriptionController::class, 'show'])->name('show');
        Route::delete('/{subscription}', [SubscriptionController::class, 'destroy'])->name('destroy');
    });
});