
<?php

use App\Http\Controllers\Admin\AdminWalletController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/admin/wallet/info', [AdminWalletController::class, 'index']);
    Route::get('/admin/wallet/transactions', [AdminWalletController::class, 'transactions']);
    Route::post('/admin/wallet/send-money', [AdminWalletController::class, 'sendMoney']);
    Route::post('/admin/wallet/withdraw', [AdminWalletController::class, 'withdraw']);

});

