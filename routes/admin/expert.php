
<?php

use App\Http\Controllers\Admin\ExpertController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/experts', [ExpertController::class, 'index'])->name('experts');
    Route::post('/expert/store', [ExpertController::class, 'store'])->name('expert.store');
    Route::get('/expert/{id}', [ExpertController::class, 'expertDetail'])->name('expert.detail');
    Route::post('/expert/edit', [ExpertController::class, 'edit'])->name('expert.edit');
    Route::delete('/expert/delete/{id}', [ExpertController::class, 'destroy'])->name('expert.destroy');
});


