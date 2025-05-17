
<?php

use App\Http\Controllers\Admin\ExpenseTypeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/expenseTypes', [ExpenseTypeController::class, 'index'])->name('expenseTypes');
    Route::post('/expenseType/store', [ExpenseTypeController::class, 'store'])->name('expenseType.store');
    Route::get('/expenseType/{id}', [ExpenseTypeController::class, 'expenseTypeDetail'])->name('expenseType.detail');
    Route::post('/expenseType/edit', [ExpenseTypeController::class, 'edit'])->name('expenseType.edit');
    Route::delete('/expenseType/delete/{id}', [ExpenseTypeController::class, 'destroy'])->name('expenseType.destroy');
});


