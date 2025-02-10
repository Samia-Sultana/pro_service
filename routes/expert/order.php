
<?php

use App\Http\Controllers\Expert\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:expert'], function(){
    Route::get('/orders/expert/{id}', [OrderController::class, 'allOrder'])->name('allOrders');

});


