
<?php

use App\Http\Controllers\Vendor\VendorWalletController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:vendor'], function(){
    Route::get('/vendor/wallet/info', [VendorWalletController::class, 'index']);
    Route::get('/vendor/wallet/transactions', [VendorWalletController::class, 'transactions']);
    Route::post('/vendor/wallet/send-money', [VendorWalletController::class, 'sendMoney']);
    Route::post('/vendor/wallet/withdraw', [VendorWalletController::class, 'withdraw']);

});

