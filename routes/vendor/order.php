
<?php

use App\Http\Controllers\Vendor\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:vendor'], function(){
    Route::get('/all/orders/{id}', [OrderController::class, 'allOrder'])->name('allOrders');
    Route::get('/order/{id}', [OrderController::class, 'orderDetail'])->name('order.detail');
    Route::post('/order/assign/expert', [OrderController::class, 'assignExpert'])->name('assignExpert');
    Route::post('/order/reschedule', [OrderController::class, 'rescheduleOrder'])->name('rescheduleOrder');

    Route::get('/vendor/income/order/{id}', [OrderController::class, 'vendorIncome'])->name('vendor.income.order');
    Route::post('/vendor-income/complete', [OrderController::class, 'markComplete']);



    // PDF
    Route::get('/order/pdf', [OrderController::class, 'orderPdf'])->name('order.pdf');


});


