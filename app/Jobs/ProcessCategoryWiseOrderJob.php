<?php

namespace App\Jobs;

use App\Models\ExpertOrder;
use App\Models\Order;
use App\Models\Vendor;
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

    public $timeout = 120;

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
        $vendors = Vendor::where('service_status', '=', 'active')->get();

        foreach ($vendors as $vendor) {
            $orderRequest = $this->createOrderRequest($vendor);

            $startTime = now();
            $statusAccepted = false;

            while (true) {
                $orderRequest->refresh();

                if ($orderRequest->status === 'accepted') {
                    $statusAccepted = true;
                    break; // Exit the waiting loop as it’s accepted
                }

                if (now()->diffInSeconds($startTime) >= 60) {
                    // Mark the request as timed out after 1 minute
                    $orderRequest->update(['status' => 'timedout']);
                    break;
                }

                sleep(5); // Wait for 5 seconds before checking again
            }

            if ($statusAccepted) {
                info("Order ID: {$this->orderId} accepted by Vendor ID: {$vendor->id}");
                break; // Stop processing further vendors
            }
        }
    }


    private function createOrderRequest($vendor)
    {
        return ExpertOrder::create([
            'order_id'    => $this->orderId,
            'category_id' => $this->categoryId,
            'status'      => 'pending',
            'vendor_id'   => $vendor->id,
        ]);
    }
}
