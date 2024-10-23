<?php

namespace App\Providers;

use App\Interfaces\Admin\AdminServiceInterface;
use App\Interfaces\Admin\PermissionServiceInterface;
use App\Interfaces\Admin\RoleServiceInterface;
use App\Services\Admin\AdminService;
use App\Services\Admin\PermissionService;
use App\Services\Admin\RoleService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminServiceInterface::class, AdminService::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
