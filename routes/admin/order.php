
<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/{id}', [OrderController::class, 'orderDetail'])->name('order.detail');
    Route::put('/order/update', [OrderController::class, 'update'])->name('order.update');
    Route::delete('/order/delete/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::post('/order/status-update', [OrderController::class, 'updateOrderStatus']);

});


