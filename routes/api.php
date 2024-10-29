<?php

use Illuminate\Support\Facades\Route;


require __DIR__ . '/admin/auth/auth.php';
require __DIR__ . '/admin/admin.php';
require __DIR__ . '/admin/permission.php';
require __DIR__ . '/admin/role.php';
require __DIR__ . '/admin/vendor.php';
require __DIR__ . '/admin/expert.php';


Route::prefix('auth')->group(function () {
    Route::get('/user', function () {
        return response()->json(auth()->user());
    });
});

// Route::get('/test', function () {
//     return "test";
// });
