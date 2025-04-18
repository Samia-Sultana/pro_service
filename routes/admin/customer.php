
<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerWalletController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customer/{id}', [CustomerController::class, 'customerDetail'])->name('customer.detail');
    Route::post('/customer/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::delete('/customer/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    Route::get('/customer/{id}/orders', [CustomerController::class, 'customerOrders'])->name('customer.orders');
    Route::post('/customer/{id}/wallet', [CustomerWalletController::class, 'addMoney']);


});


