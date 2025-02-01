
<?php

use App\Http\Controllers\Expert\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/all/orders', [OrderController::class, 'allOrder'])->name('allOrders');

});


