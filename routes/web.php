<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SalesOrderSubmissionController;

Route::get('/', function () {
    return view('home');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public customer order form routes (no auth required)
Route::get('/order/{uniqueLink}', [SalesOrderSubmissionController::class, 'showForm'])->name('order.form');
Route::post('/order/{uniqueLink}', [SalesOrderSubmissionController::class, 'submit'])->name('order.submit');
Route::get('/invoice/{id}', [SalesOrderSubmissionController::class, 'showInvoice'])->name('invoice.show');

// Public partner progress routes (no auth required)
Route::get('/progress/{uniqueLink}', [\App\Http\Controllers\OrderProgressController::class, 'showProgress'])->name('progress.show');
Route::post('/progress/{uniqueLink}', [\App\Http\Controllers\OrderProgressController::class, 'updateProgress'])->name('progress.update');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('sales-orders', SalesOrderController::class);
    Route::get('/receiving-report', [SalesOrderSubmissionController::class, 'index'])->name('receiving-report');
    Route::post('/receiving-report/{id}/confirm', [\App\Http\Controllers\AccountReceivableController::class, 'confirmOrder'])->name('receiving-report.confirm');
    
    Route::get('/account-receivables', [\App\Http\Controllers\AccountReceivableController::class, 'index'])->name('account-receivables.index');
    Route::post('/account-receivables/{id}/payment', [\App\Http\Controllers\AccountReceivableController::class, 'recordPayment'])->name('account-receivables.payment');
    
    Route::get('/accounts-payable', [\App\Http\Controllers\AccountPayableController::class, 'index'])->name('accounts-payable.index');
    Route::post('/accounts-payable/{id}/payment', [\App\Http\Controllers\AccountPayableController::class, 'recordPayment'])->name('accounts-payable.payment');
    
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/status', [\App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{id}/generate-link', [\App\Http\Controllers\OrderProgressController::class, 'generateLink'])->name('orders.generate-link');
});
