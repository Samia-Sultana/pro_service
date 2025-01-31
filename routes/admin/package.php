
<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryPackageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/packages', action: [CategoryPackageController::class, 'index'])->name('packages');
    Route::post('/package/store', [CategoryPackageController::class, 'store'])->name('package.store');
    Route::get('/package/{id}', [CategoryPackageController::class, 'packageDetail'])->name('package.detail');
    Route::post('/package/edit', [CategoryPackageController::class, 'edit'])->name('package.edit');
    Route::delete('/package/delete/{id}', [CategoryPackageController::class, 'destroy'])->name('package.destroy');
});


