
<?php

use App\Http\Controllers\Admin\VendorController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/vendors', [VendorController::class, 'index'])->name('vendors');
    Route::post('/vendor/store', [VendorController::class, 'store'])->name('vendor.store');
    Route::get('/vendor/{id}', [VendorController::class, 'vendorDetail'])->name('vendor.detail');
    Route::post('/vendor/edit', [VendorController::class, 'edit'])->name('vendor.edit');
    Route::delete('/vendor/delete/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');
});


