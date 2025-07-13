
<?php

use App\Http\Controllers\Vendor\VendorIncomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:vendor'], function(){
    Route::get('/vendor/income/{id}', [VendorIncomeController::class, 'index']);
    Route::post('/vendor/income/status-update', [VendorIncomeController::class, 'incomeStatusUpdate']);

});


