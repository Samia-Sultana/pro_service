<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessCategoryWiseOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderId;
    public $categoryId;
    public $packages;

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderId, int $categoryId, array $packages)
    {
        $this->orderId = $orderId;
        $this->categoryId = $categoryId;
        $this->packages = $packages;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        info("Processing Order ID: {$this->orderId} for Category ID: {$this->categoryId}, Packages: " . json_encode($this->packages));

        // info($this->packages);
    }
}
