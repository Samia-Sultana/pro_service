<?php

namespace App\Providers;

use App\Interfaces\Admin\AdminServiceInterface;
use App\Interfaces\Admin\CategoryServiceInterface;
use App\Interfaces\Admin\CustomerServiceInterface;
use App\Interfaces\Admin\ExpertServiceInterface;
use App\Interfaces\Admin\CategoryPackageServiceInterface;
use App\Interfaces\Admin\PermissionServiceInterface;
use App\Interfaces\Admin\RoleServiceInterface;
use App\Interfaces\Admin\SubcategoryServiceInterface;
use App\Interfaces\Admin\OrderServiceInterface;
use App\Interfaces\Admin\OrderPackageInterface;
use App\Interfaces\Admin\VendorServiceInterface;
use App\Services\Admin\AdminService;
use App\Services\Admin\CategoryService;
use App\Services\Admin\ExpertService;
use App\Services\Admin\CategoryPackageService;
use App\Services\Admin\orderPackageService;
use App\Services\Admin\PermissionService;
use App\Services\Admin\RoleService;
use App\Services\Admin\SubcategoryService;
use App\Services\Admin\VendorService;
use App\Services\Admin\CustomerService;
use App\Services\Admin\OrderService;

use App\Interfaces\Expert\OrderServiceInterface as ExpertOrderServiceInterface;
use App\Services\Expert\OrderService as ExpertOrderService;




use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(AdminServiceInterface::class, AdminService::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(VendorServiceInterface::class, VendorService::class);
        $this->app->bind(ExpertServiceInterface::class, ExpertService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(SubcategoryServiceInterface::class, SubcategoryService::class);
        $this->app->bind( CustomerServiceInterface::class, CustomerService::class);
        $this->app->bind(  CategoryPackageServiceInterface::class, CategoryPackageService::class);
        $this->app->bind( OrderServiceInterface::class, OrderService::class);

        $this->app->bind( OrderPackageInterface::class, orderPackageService::class);
        $this->app->bind(ExpertOrderServiceInterface::class, ExpertOrderService::class);



    }


    public function boot(): void
    {

    }
}
