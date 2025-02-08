
<?php

use App\Http\Controllers\Vendor\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:vendor'], function(){
    Route::get('/all/orders/{id}', [OrderController::class, 'allOrder'])->name('allOrders');

});


