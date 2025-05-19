
<?php

use App\Http\Controllers\Admin\IncomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/incomes', [IncomeController::class, 'index'])->name('incomes');
    Route::post('/income/store', [IncomeController::class, 'store'])->name('income.store');
    Route::get('/income/{id}', [IncomeController::class, 'incomeDetail'])->name('income.detail');
    Route::post('/income/edit', [IncomeController::class, 'edit'])->name('income.edit');
    Route::delete('/income/delete/{id}', [IncomeController::class, 'destroy'])->name('income.destroy');
});


