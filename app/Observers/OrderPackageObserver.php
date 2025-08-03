<?php

namespace App\Observers;

use App\Jobs\ProcessNewOrderPackageJob;
use App\Models\OrderPackage;

class OrderPackageObserver
{
    /**
     * Handle the OrderPackage "created" event.
     */
    public function created(OrderPackage $OrderPackage): void
    {
        ProcessNewOrderPackageJob::dispatch($OrderPackage);
    }

    /**
     * Handle the OrderPackage "updated" event.
     */
    public function updated(OrderPackage $OrderPackage): void
    {
        //
    }

    /**
     * Handle the OrderPackage "deleted" event.
     */
    public function deleted(OrderPackage $OrderPackage): void
    {
        //
    }

    /**
     * Handle the OrderPackage "restored" event.
     */
    public function restored(OrderPackage $OrderPackage): void
    {
        //
    }

    /**
     * Handle the OrderPackage "force deleted" event.
     */
    public function forceDeleted(OrderPackage $OrderPackage): void
    {
        //
    }
}
