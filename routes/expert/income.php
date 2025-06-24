
<?php

use App\Http\Controllers\Expert\ExpertIncomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:expert'], function(){
    Route::get('/expert/income/{id}', [ExpertIncomeController::class, 'index']);

});


