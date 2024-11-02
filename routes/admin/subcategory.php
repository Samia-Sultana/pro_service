
<?php

use App\Http\Controllers\Admin\SubcategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/subcategories', [SubcategoryController::class, 'index'])->name('subcategories');

});


