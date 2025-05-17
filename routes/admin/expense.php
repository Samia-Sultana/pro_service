
<?php

use App\Http\Controllers\Admin\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::post('/expense/store', [ExpenseController::class, 'store'])->name('expense.store');
    Route::get('/expense/{id}', [ExpenseController::class, 'expenseDetail'])->name('expense.detail');
    Route::post('/expense/edit', [ExpenseController::class, 'edit'])->name('expense.edit');
    Route::delete('/expense/delete/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
});


