<?php

namespace App\Jobs;

use App\Models\OrderPackage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\ProcessCategoryWiseOrderJob;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessNewOrderPackageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderPackage;

    /**
     * Create a new job instance.
     */
    public function __construct( OrderPackage $orderPackage)
    {
        $this->orderPackage = $orderPackage;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $orderPackage = OrderPackage::find($this->orderPackage->id);

        if (!$orderPackage) {
            return;
        }



    }
}
