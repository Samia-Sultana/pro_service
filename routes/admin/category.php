
<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/{id}', [CategoryController::class, 'categoryDetail'])->name('category.detail');
    Route::post('/category/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::delete('/category/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
});


