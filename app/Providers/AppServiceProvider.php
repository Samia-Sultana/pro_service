<?php

namespace App\Providers;

use App\Interfaces\Vendor\VendorIncomeServiceInterface;
use App\Models\Order;
use App\Observers\OrderObserver;
use App\Services\Admin\RoleService;
use App\Services\Admin\AdminService;
use App\Services\Admin\OrderService;
use App\Services\Admin\ExpertService;
use App\Services\Admin\IncomeService;
use App\Services\Vendor\VendorIncomeService;
use App\Services\Admin\VendorService;
use App\Services\Admin\ExpenseService;
use App\Services\Admin\CategoryService;
use App\Services\Admin\CustomerService;
use Illuminate\Support\ServiceProvider;
use App\Services\Admin\PermissionService;
use App\Services\Admin\ExpenseTypeService;
use App\Services\Admin\SubcategoryService;
use App\Services\Expert\ExpertIncomeService;
use App\Services\Admin\OrderPackageService;
use App\Services\Admin\CustomerWalletService;
use App\Interfaces\Admin\RoleServiceInterface;
use App\Services\Admin\CategoryPackageService;
use App\Interfaces\Admin\AdminServiceInterface;
use App\Interfaces\Admin\OrderPackageInterface;
use App\Interfaces\Admin\OrderServiceInterface;
use App\Interfaces\Admin\ExpertServiceInterface;
use App\Interfaces\Admin\IncomeServiceInterface;
use App\Interfaces\Admin\VendorServiceInterface;
use App\Interfaces\Admin\ExpenseServiceInterface;
use App\Interfaces\Admin\CategoryServiceInterface;
use App\Interfaces\Admin\CustomerServiceInterface;
use App\Interfaces\Admin\PermissionServiceInterface;
use App\Interfaces\Admin\ExpenseTypeServiceInterface;
use App\Interfaces\Admin\SubcategoryServiceInterface;


use App\Interfaces\Expert\ExpertIncomeServiceInterface;
use App\Interfaces\Admin\CustomerWalletServiceInterface;

use App\Interfaces\Admin\CategoryPackageServiceInterface;
use App\Services\Expert\OrderService as ExpertOrderService;

use App\Services\Vendor\OrderService as VendorOrderService;
use App\Services\Vendor\ExpertService as VendorExpertService;
use App\Interfaces\Expert\OrderServiceInterface as ExpertOrderServiceInterface;
use App\Interfaces\Vendor\OrderServiceInterface as VendorOrderServiceInterface;
use App\Interfaces\Vendor\ExpertServiceInterface as VendorExpertServiceInterface;



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

        $this->app->bind( OrderPackageInterface::class, OrderPackageService::class);
        $this->app->bind(ExpertOrderServiceInterface::class, ExpertOrderService::class);
        $this->app->bind(VendorOrderServiceInterface::class, VendorOrderService::class);
        $this->app->bind(VendorExpertServiceInterface::class, VendorExpertService::class);
        $this->app->bind(CustomerWalletServiceInterface::class, CustomerWalletService::class);

        $this->app->bind(ExpenseServiceInterface::class, ExpenseService::class);
        $this->app->bind(ExpenseTypeServiceInterface::class, ExpenseTypeService::class);

        $this->app->bind(IncomeServiceInterface::class, IncomeService::class);
        $this->app->bind(ExpertIncomeServiceInterface::class, ExpertIncomeService::class);
        $this->app->bind(VendorIncomeServiceInterface::class, VendorIncomeService::class);



    }


    public function boot(): void
    {
        Order::observe(OrderObserver::class);
    }
}
