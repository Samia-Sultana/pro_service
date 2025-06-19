<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\Income;
use App\Models\Wallet;
use App\Models\OrderPackage;
use App\Models\ExpertOrder;
use App\Models\VendorIncome;

class IncomeHelper
{
    /**
     * Calculate and record admin income, then add to admin wallet
     *
     * @param Order $order
     * @return void
     */
    public static function processAdminIncome(Order $order): void
    {
        $netAmount = $order->order_amount - $order->discount;
        $adminIncome = $netAmount * 0.10;

        // Create admin income record
        Income::create([
            'order_id' => $order->id,
            'income_amount' => $adminIncome,
        ]);

        // Increment admin wallet (assuming admin is user ID 4)
        Wallet::where('walletable_id', 4)
            ->where('walletable_type', 'App\Models\User')
            ->increment('balance', $adminIncome);
    }

    /**
     * Calculate vendor income and add to their wallets
     *
     * @param Order $order
     * @return void
     */
    public static function processVendorIncome(Order $order): void
    {
        $orderPackages = OrderPackage::where('order_id', $order->id)->get();
        $expertOrders = ExpertOrder::where('order_id', $order->id)
            ->where('status', '!=', 'timedout')
            ->get();

        // Build category payable map
        $categoryPayables = [];
        foreach ($orderPackages as $package) {
            $net = $package->price - $package->discount;
            $categoryPayables[$package->category_id] = ($categoryPayables[$package->category_id] ?? 0) + $net;
        }

        // Calculate vendor income
        $vendorAmounts = [];
        foreach ($expertOrders as $item) {
            $vendorId = $item->vendor_id;
            $categoryId = $item->category_id;

            if (!isset($categoryPayables[$categoryId])) {
                continue;
            }

            $vendorAmounts[$vendorId] = ($vendorAmounts[$vendorId] ?? 0) + $categoryPayables[$categoryId];
        }

        // Record and credit vendor income
        foreach ($vendorAmounts as $vendorId => $amount) {
            VendorIncome::create([
                'order_id' => $order->id,
                'vendor_id' => $vendorId,
                'income_amount' => $amount,
            ]);

            Wallet::where('walletable_id', $vendorId)
                ->where('walletable_type', 'App\Models\Vendor')
                ->increment('balance', $amount);
        }
    }
}
