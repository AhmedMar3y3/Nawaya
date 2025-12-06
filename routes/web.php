<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\DR_HopeController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\WorkshopController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SupportMessageController;
use App\Http\Controllers\Admin\FinancialCenterController;


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
        Route::get('/'                                   , [FinancialCenterController::class, 'index'])->name('index');
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
        Route::get('/workshops/{workshopId}/payments'    , [FinancialCenterController::class, 'getWorkshopPayments'])->name('workshops.payments');
        Route::put('/workshops/{workshopId}/teacher-per' , [FinancialCenterController::class, 'updateTeacherPercentage'])->name('workshops.update-teacher-per');
        Route::post('/workshops/{workshopId}/payments'   , [FinancialCenterController::class, 'addPayment'])->name('workshops.add-payment');
        Route::delete('/payments/{paymentId}'            , [FinancialCenterController::class, 'deletePayment'])->name('payments.delete');
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

    // Subscriptions Routes
    Route::prefix('subscriptions')->name('admin.subscriptions.')->group(function () {
        Route::get('/'                    , [SubscriptionController::class, 'index'])->name('index');
        Route::post('/'                   , [SubscriptionController::class, 'store'])->name('store');
        Route::get('/search-users'        , [SubscriptionController::class, 'searchUsers'])->name('search-users');
        Route::get('/packages'            , [SubscriptionController::class, 'getPackages'])->name('packages');
        Route::get('/export/excel'        , [SubscriptionController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf'          , [SubscriptionController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/{id}/edit'           , [SubscriptionController::class, 'edit'])->name('edit');
        Route::get('/{id}/invoice'        , [SubscriptionController::class, 'getInvoice'])->name('invoice');
        Route::get('/{id}/invoice/pdf'    , [SubscriptionController::class, 'downloadInvoicePdf'])->name('invoice.pdf');
        Route::get('/{id}/user-details'   , [SubscriptionController::class, 'getUserDetails'])->name('user-details');
        Route::put('/{id}'                , [SubscriptionController::class, 'update'])->name('update');
        Route::post('/{id}/transfer-to-balance', [SubscriptionController::class, 'transferToInternalBalance'])->name('transfer-to-balance');
        Route::post('/{id}/transfer'      , [SubscriptionController::class, 'transfer'])->name('transfer');
        Route::post('/{id}/refund'        , [SubscriptionController::class, 'processRefund'])->name('refund');
        Route::get('/pending-approvals'   , [SubscriptionController::class, 'getPendingApprovals'])->name('pending-approvals');
        Route::post('/{id}/approve'       , [SubscriptionController::class, 'approveSubscription'])->name('approve');
        Route::post('/{id}/reject'        , [SubscriptionController::class, 'rejectSubscription'])->name('reject');
        Route::get('/pending-approvals/export'   , [SubscriptionController::class, 'exportPendingApprovalsExcel'])->name('pending-approvals.export');
        Route::get('/transfers'                  , [SubscriptionController::class, 'getTransfers'])->name('transfers');
        Route::get('/refunds'                    , [SubscriptionController::class, 'getRefunds'])->name('refunds');
        Route::get('/balance-subscriptions'      , [SubscriptionController::class, 'getBalanceSubscriptions'])->name('balance-subscriptions');
        Route::get('/debts'                      , [SubscriptionController::class, 'getDebts'])->name('debts');
        Route::get('/users-balances'             , [SubscriptionController::class, 'getUsersBalances'])->name('users-balances');
        Route::delete('/balance-history/{id}'    , [SubscriptionController::class, 'deleteBalanceHistory'])->name('balance-history.delete');
        Route::post('/balance-history/{id}/restore'       , [SubscriptionController::class, 'restoreBalanceHistory'])->name('balance-history.restore');
        Route::delete('/balance-history/{id}/permanent'   , [SubscriptionController::class, 'permanentlyDeleteBalanceHistory'])->name('balance-history.permanent-delete');
        Route::get('/gift-subscriptions'                  , [SubscriptionController::class, 'getGiftSubscriptions'])->name('gift-subscriptions');
        Route::delete('/gift-subscriptions/{id}'          , [SubscriptionController::class, 'deleteGiftSubscription'])->name('gift-subscriptions.delete');
        Route::post('/gift-subscriptions/{id}/restore'    , [SubscriptionController::class, 'restoreGiftSubscription'])->name('gift-subscriptions.restore');
        Route::delete('/gift-subscriptions/{id}/permanent', [SubscriptionController::class, 'permanentlyDeleteGiftSubscription'])->name('gift-subscriptions.permanent-delete');
        Route::post('/gift-subscriptions/{id}/approve'    , [SubscriptionController::class, 'approveGiftSubscription'])->name('gift-subscriptions.approve');
        Route::post('/gift-subscriptions/{id}/transfer'   , [SubscriptionController::class, 'transferGiftSubscription'])->name('gift-subscriptions.transfer');
        Route::post('/{id}/reactivate'                    , [SubscriptionController::class, 'reactivateSubscription'])->name('reactivate');
        Route::get('/workshop/{workshopId}/stats'         , [SubscriptionController::class, 'getWorkshopSubscriptionsStats'])->name('workshop.stats');
        Route::get('/workshop/{workshopId}/stats/export/excel', [SubscriptionController::class, 'exportWorkshopStatsExcel'])->name('workshop.stats.export.excel');
        Route::get('/workshop/{workshopId}/stats/export/pdf'  , [SubscriptionController::class, 'exportWorkshopStatsPdf'])->name('workshop.stats.export.pdf');
        Route::post('/{id}/restore'       , [SubscriptionController::class, 'restore'])->name('restore');
        Route::delete('/{id}/permanent'   , [SubscriptionController::class, 'permanentlyDelete'])->name('permanent-delete');
        Route::delete('/{id}'             , [SubscriptionController::class, 'destroy'])->name('destroy');
    });
});