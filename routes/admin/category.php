
<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');

});


