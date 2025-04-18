
<?php

use App\Http\Controllers\Expert\ExpertWalletController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:expert'], function(){
    Route::get('/expert/wallet/info', [ExpertWalletController::class, 'index']);
    Route::get('/expert/wallet/transactions', [ExpertWalletController::class, 'transactions']);
    Route::post('/expert/wallet/send-money', [ExpertWalletController::class, 'sendMoney']);
    Route::post('/expert/wallet/withdraw', [ExpertWalletController::class, 'withdraw']);

});

