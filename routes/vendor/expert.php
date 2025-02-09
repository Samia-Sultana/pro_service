
<?php

use App\Http\Controllers\Vendor\ExpertController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:vendor'], function(){
    Route::get('/all/expert/{id}', [ExpertController::class, 'allExpert'])->name('allExpert');


});


