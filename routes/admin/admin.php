
<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/admins', [AdminController::class, 'index'])->name('admins');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/{id}', [AdminController::class, 'adminDetail'])->name('admin.detail');
    Route::post('/admin/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::delete('/admin/delete/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
});


