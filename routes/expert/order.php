
<?php

use App\Http\Controllers\Expert\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:expert'], function(){
    Route::get('/all/orders/{id}', [OrderController::class, 'allOrder'])->name('allOrders');

});


