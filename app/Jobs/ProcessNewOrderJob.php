<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\ProcessCategoryWiseOrderJob;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessNewOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     */
    public function __construct( Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $order = Order::with('orderPackages')->find($this->order->id);

        if (!$order) {
            return;
        }

        if (empty($order->orderPackages)) {
            return;
        }

        $groupedPackages = collect($order->orderPackages)->groupBy('category_id');

        foreach ($groupedPackages as $categoryId => $packages) {
            ProcessCategoryWiseOrderJob::dispatch($order->id, $order->slot, $order->date, $categoryId, $packages->toArray());
        }

    }
}
