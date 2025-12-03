<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\DR_HopeController;
use App\Http\Controllers\Admin\WorkshopController;
use App\Http\Controllers\Admin\SupportMessageController;
use App\Http\Controllers\Admin\FinancialCenterController;
use App\Http\Controllers\Admin\ExpenseController;


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
        Route::get('/{type}'      , [DR_HopeController::class, 'getByType'])->name('getByType');
        Route::get('/{id}/show'   , [DR_HopeController::class, 'show'])->name('show');
        Route::post('/'           , [DR_HopeController::class, 'store'])->name('store');
        Route::match(['put', 'post'], '/{id}', [DR_HopeController::class, 'update'])->name('update');
        Route::delete('/{id}'     , [DR_HopeController::class, 'destroy'])->name('destroy');
    });

    // Partners Routes
    Route::prefix('partners')->name('admin.partners.')->group(function () {
        Route::get('/'            , [PartnerController::class, 'index'])->name('index');
        Route::get('/{id}/show'   , [PartnerController::class, 'show'])->name('show');
        Route::post('/'           , [PartnerController::class, 'store'])->name('store');
        Route::match(['put', 'post'], '/{id}', [PartnerController::class, 'update'])->name('update');
        Route::delete('/{id}'     , [PartnerController::class, 'destroy'])->name('destroy');
    });

    // Workshops Routes
    Route::prefix('workshops')->name('admin.workshops.')->group(function () {
        Route::get('/'                    , [WorkshopController::class, 'index'])->name('index');
        Route::get('/create'              , [WorkshopController::class, 'create'])->name('create');
        Route::post('/'                   , [WorkshopController::class, 'store'])->name('store');
        Route::get('/export/excel'        , [WorkshopController::class, 'exportExcel'])->name('export.excel');
        Route::get('/{id}/show'           , [WorkshopController::class, 'show'])->name('show');
        Route::get('/{id}/edit'           , [WorkshopController::class, 'edit'])->name('edit');
        Route::put('/{id}'                , [WorkshopController::class, 'update'])->name('update');
        Route::delete('/{id}'             , [WorkshopController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore'       , [WorkshopController::class, 'restore'])->name('restore');
        Route::delete('/{id}/permanent'   , [WorkshopController::class, 'permanentlyDelete'])->name('permanent-delete');
        Route::post('/{id}/toggle-status' , [WorkshopController::class, 'toggleStatus'])->name('toggle-status');
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

    // Orders Routes (Boutique Management)
    Route::prefix('orders')->name('admin.orders.')->group(function () {
        Route::get('/'                    , [OrderController::class, 'index'])->name('index');
        Route::get('/{id}/items'          , [OrderController::class, 'getOrderItems'])->name('items');
        Route::get('/{id}/user'           , [OrderController::class, 'getUserDetails'])->name('user');
        Route::post('/{id}/complete'      , [OrderController::class, 'markOrderCompleted'])->name('complete');
        Route::delete('/{id}'             , [OrderController::class, 'deleteOrder'])->name('destroy');
        Route::post('/{id}/restore'       , [OrderController::class, 'restoreOrder'])->name('restore');
        Route::delete('/{id}/permanent'   , [OrderController::class, 'permanentlyDeleteOrder'])->name('permanent');
    });

    // Financial Center Routes
    Route::prefix('financial-center')->name('admin.financial-center.')->group(function () {
        Route::get('/'                                    , [FinancialCenterController::class, 'index'])->name('index');
        Route::get('/export/excel'                       , [FinancialCenterController::class, 'exportExcel'])->name('export.excel');
        Route::get('/annual-tax/details'                 , [FinancialCenterController::class, 'getAnnualTaxDetails'])->name('annual-tax.details');
        Route::get('/annual-tax/export/pdf'              , [FinancialCenterController::class, 'exportAnnualTaxPdf'])->name('annual-tax.export.pdf');
        Route::get('/annual-tax/export/excel'            , [FinancialCenterController::class, 'exportAnnualTaxExcel'])->name('annual-tax.export.excel');
        Route::get('/vat-report'                         , [FinancialCenterController::class, 'getVatReport'])->name('vat-report');
        Route::get('/vat-report/export/pdf'              , [FinancialCenterController::class, 'exportVatPdf'])->name('vat-report.export.pdf');
        Route::get('/vat-report/export/excel'            , [FinancialCenterController::class, 'exportVatExcel'])->name('vat-report.export.excel');
        Route::get('/refundable-tax'                     , [FinancialCenterController::class, 'getRefundableTaxReport'])->name('refundable-tax');
        Route::get('/refundable-tax/export/pdf'          , [FinancialCenterController::class, 'exportRefundableTaxPdf'])->name('refundable-tax.export.pdf');
        Route::get('/refundable-tax/export/excel'        , [FinancialCenterController::class, 'exportRefundableTaxExcel'])->name('refundable-tax.export.excel');
        Route::get('/workshops/{workshopId}/payments'     , [FinancialCenterController::class, 'getWorkshopPayments'])->name('workshops.payments');
        Route::put('/workshops/{workshopId}/teacher-per'   , [FinancialCenterController::class, 'updateTeacherPercentage'])->name('workshops.update-teacher-per');
        Route::post('/workshops/{workshopId}/payments'     , [FinancialCenterController::class, 'addPayment'])->name('workshops.add-payment');
        Route::delete('/payments/{paymentId}'              , [FinancialCenterController::class, 'deletePayment'])->name('payments.delete');
    });

    // Expenses Routes
    Route::prefix('expenses')->name('admin.expenses.')->group(function () {
        Route::get('/'                    , [ExpenseController::class, 'index'])->name('index');
        Route::post('/'                   , [ExpenseController::class, 'store'])->name('store');
        Route::get('/{id}'                , [ExpenseController::class, 'show'])->name('show');
        Route::put('/{id}'                , [ExpenseController::class, 'update'])->name('update');
        Route::delete('/{id}'             , [ExpenseController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore'       , [ExpenseController::class, 'restore'])->name('restore');
        Route::delete('/{id}/permanent'   , [ExpenseController::class, 'permanentlyDelete'])->name('permanent-delete');
        Route::get('/export/excel'        , [ExpenseController::class, 'exportExcel'])->name('export.excel');
    });
    });