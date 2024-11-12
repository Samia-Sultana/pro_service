
<?php

use App\Http\Controllers\Admin\SubcategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/subcategories', [SubcategoryController::class, 'index'])->name('subcategories');
    Route::post('/subcategory/store', [SubcategoryController::class, 'store'])->name('subcategory.store');
    Route::get('/subcategory/{id}', [SubcategoryController::class, 'subcategoryDetail'])->name('subcategory.detail');
    Route::post('/subcategory/edit', [SubcategoryController::class, 'edit'])->name('subcategory.edit');
    Route::delete('/subcategory/delete/{id}', [SubcategoryController::class, 'destroy'])->name('subcategory.destroy');
});


