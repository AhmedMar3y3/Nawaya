<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupportMessageController;
use App\Http\Controllers\Admin\ProductController;


// public routes //
Route::get('/'            ,[AuthController::class, 'loadLoginPage'])->name('loginPage');
Route::post('/login-admin',[AuthController::class, 'loginUser'])->name('loginUser');

// protected routes //
Route::middleware(['auth.admin'])->group(function () {
    Route::post('/logout'  ,[AuthController::class, 'logout'])->name('admin.logout'); 
    Route::get('/dashboard',[AuthController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Routes
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/'                    , [UserController::class, 'index'])->name('index');
        Route::get('/create'              , [UserController::class, 'create'])->name('create');
        Route::post('/'                   , [UserController::class, 'store'])->name('store');
        Route::get('/export/excel'        , [UserController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf'          , [UserController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/{id}/show'           , [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit'           , [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}'                , [UserController::class, 'update'])->name('update');
        Route::delete('/{id}'             , [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore'       , [UserController::class, 'restore'])->name('restore');
        Route::delete('/{id}/permanent'   , [UserController::class, 'permanentlyDelete'])->name('permanent-delete');
    });

    // setting routes //
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // Support Messages Routes
    Route::prefix('support-messages')->name('admin.support-messages.')->group(function () {
        Route::get('/'         , [SupportMessageController::class, 'index'])->name('index');
        Route::get('/{id}/show', [SupportMessageController::class, 'show'])->name('show');
        Route::delete('/{id}'  , [SupportMessageController::class, 'destroy'])->name('destroy');
    });

    // DR Hope Routes
    Route::prefix('dr-hope')->name('admin.dr-hope.')->group(function () {
        Route::get('/{type}'     , [\App\Http\Controllers\Admin\DR_HopeController::class, 'getByType'])->name('getByType');
        Route::get('/{id}/show'   , [\App\Http\Controllers\Admin\DR_HopeController::class, 'show'])->name('show');
        Route::post('/'           , [\App\Http\Controllers\Admin\DR_HopeController::class, 'store'])->name('store');
        Route::match(['put', 'post'], '/{id}', [\App\Http\Controllers\Admin\DR_HopeController::class, 'update'])->name('update');
        Route::delete('/{id}'     , [\App\Http\Controllers\Admin\DR_HopeController::class, 'destroy'])->name('destroy');
    });

    // Partners Routes
    Route::prefix('partners')->name('admin.partners.')->group(function () {
        Route::get('/'           , [\App\Http\Controllers\Admin\PartnerController::class, 'index'])->name('index');
        Route::get('/{id}/show'   , [\App\Http\Controllers\Admin\PartnerController::class, 'show'])->name('show');
        Route::post('/'           , [\App\Http\Controllers\Admin\PartnerController::class, 'store'])->name('store');
        Route::match(['put', 'post'], '/{id}', [\App\Http\Controllers\Admin\PartnerController::class, 'update'])->name('update');
        Route::delete('/{id}'     , [\App\Http\Controllers\Admin\PartnerController::class, 'destroy'])->name('destroy');
    });

    // Workshops Routes
    Route::prefix('workshops')->name('admin.workshops.')->group(function () {
        Route::get('/'                    , [\App\Http\Controllers\Admin\WorkshopController::class, 'index'])->name('index');
        Route::get('/create'              , [\App\Http\Controllers\Admin\WorkshopController::class, 'create'])->name('create');
        Route::post('/'                   , [\App\Http\Controllers\Admin\WorkshopController::class, 'store'])->name('store');
        Route::get('/export/excel'        , [\App\Http\Controllers\Admin\WorkshopController::class, 'exportExcel'])->name('export.excel');
        Route::get('/{id}/show'           , [\App\Http\Controllers\Admin\WorkshopController::class, 'show'])->name('show');
        Route::get('/{id}/edit'           , [\App\Http\Controllers\Admin\WorkshopController::class, 'edit'])->name('edit');
        Route::put('/{id}'                , [\App\Http\Controllers\Admin\WorkshopController::class, 'update'])->name('update');
        Route::delete('/{id}'             , [\App\Http\Controllers\Admin\WorkshopController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore'       , [\App\Http\Controllers\Admin\WorkshopController::class, 'restore'])->name('restore');
        Route::delete('/{id}/permanent'   , [\App\Http\Controllers\Admin\WorkshopController::class, 'permanentlyDelete'])->name('permanent-delete');
        Route::post('/{id}/toggle-status' , [\App\Http\Controllers\Admin\WorkshopController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Products Routes (Boutique Management)
    Route::prefix('products')->name('admin.products.')->group(function () {
        Route::get('/'                    , [ProductController::class, 'index'])->name('index');
        Route::get('/create'              , [ProductController::class, 'create'])->name('create');
        Route::post('/'                   , [ProductController::class, 'store'])->name('store');
        Route::get('/{id}/show'           , [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit'           , [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}'                , [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}'             , [ProductController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore'       , [ProductController::class, 'restore'])->name('restore');
        Route::delete('/{id}/permanent'   , [ProductController::class, 'permanentlyDelete'])->name('permanent-delete');
    });
});