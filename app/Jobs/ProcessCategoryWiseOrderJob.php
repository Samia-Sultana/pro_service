<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Vendor;
use App\Models\ExpertOrder;
use Illuminate\Queue\SerializesModels;
use App\Notifications\OrderNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class ProcessCategoryWiseOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderId;
    public $categoryId;
    public $packages;
    public $vendorIndex;

    public $timeout = 120;

    public function __construct(int $orderId, int $categoryId, array $packages, int $vendorIndex = 0)
    {
        $this->orderId = $orderId;
        $this->categoryId = $categoryId;
        $this->packages = $packages;
        $this->vendorIndex = $vendorIndex;  // Tracks which vendor to process next
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $vendors = Vendor::where('service_status', '=', 'active')->get();

        if ($this->vendorIndex >= $vendors->count()) {
            info("All vendors processed for Order ID: {$this->orderId}, Category ID: {$this->categoryId}");
            return; // All vendors have been processed
        }

        $vendor = $vendors[$this->vendorIndex];
        $orderRequest = $this->createOrderRequest($vendor);


        if ($orderRequest) {
            Notification::send($orderRequest, new OrderNotification($orderRequest)); // Send the notification
        }

        // Wait for 1 minute and recheck the status
        sleep(60); // Wait for 1 minute (you can adjust this if necessary)

        $orderRequest->refresh();

        if ($orderRequest->status === 'accepted') {
            info("Order ID: {$this->orderId} accepted by Vendor ID: {$vendor->id}");
            return; // Exit as the request has been accepted
        }

        // Mark the request as timed out
        $orderRequest->update(['status' => 'timedout']);

        // Dispatch the job again for the next vendor
        ProcessCategoryWiseOrderJob::dispatch($this->orderId, $this->categoryId, $this->packages, $this->vendorIndex + 1)
            ->delay(now()->addMinute()); // Add a 1-minute delay before processing the next vendor
    }

    /**
     * Create a new order request for the vendor.
     */
    private function createOrderRequest($vendor)
    {
        return ExpertOrder::create([
            'order_id'    => $this->orderId,
            'category_id' => $this->categoryId,
            'status'      => 'pending',
            'vendor_id'   => $vendor->id,
        ]);
    }

    // public function sendNotification($expertOrder)
    // {
    //    // Retrieve the vendor who should receive the notification
    //     info($expertOrder->vendor_id);

    //     // Find the vendor
    //     $vendor = Vendor::all();

    //     info($vendor);

    //     // Check if the vendor exists
    //     if ($vendor) {
    //         Notification::send($vendor, new OrderNotification($expertOrder));
    //     } else {
    //         // Log if the vendor is not found
    //         info("Vendor ID: {$expertOrder->vendor_id} not found for Order ID: {$this->orderId}");
    //     }
    // }
}
