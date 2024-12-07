
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/profile', [AuthController::class, 'authUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'authUser']);
    Route::get('/vendor/dashboard', [AuthController::class, 'vedorDashboard'])->name('vendorDashboard');
    Route::get('/expert/dashboard', [AuthController::class, 'expertDashboard'])->name('expertDashboard');


});
