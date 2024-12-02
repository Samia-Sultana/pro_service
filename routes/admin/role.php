
<?php

use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:user'], function(){
    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/{id}/permissions', [RoleController::class, 'getPermissions'])->name('role.detail');
    Route::post('/role/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::delete('/role/delete/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
});


