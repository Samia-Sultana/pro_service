<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Interfaces\Admin\OrderServiceInterface;
use App\Models\Customer;
use App\Models\ExpertOrder;
use App\Models\Income;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\VendorIncome;
use App\Models\Wallet;
use DB;

class OrderService implements OrderServiceInterface
{
    private Order $orderModel;
    public function __construct(Order $orderModel)
    {
        $this->orderModel = $orderModel;
    }
    public function index($search = null)
    {
        $query = $this->orderModel->with('customer');
        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        return $query->paginate(10);
    }
    public function store(array $data)
    {

        $query = $this->orderModel->query();
        $order = $query->create([
            'customer_id' => $data['customer_id'],
            'order_type' => $data['order_type'],
            'area' => $data['area'],
            'house_no' => $data['house_no'],
            'road_no' => $data['road_no'],
            'block' => $data['block'],
            'district' => $data['district'],
            'additional_info' => $data['additional_info'] ?? null,
            'order_amount' => $data['order_amount'],
            'discount' => $data['discount'] ?? 0,
            'cupon' => $data['cupon'] ?? null,
            'description' => $data['description'] ?? null,
            'date' => $data['date'],
            'slot' => $data['slot'],
            'status' => $data['status'] ?? 'pending',
        ]);
        return $order;

    }

    public function orderDetail($id)
    {

        $order = $this->orderModel->with(['orderPackages.category', 'orderPackages.categoryPackage'])->where('id', '=', $id)->first();
        return $order;

    }

    public function updateOrderStatus($data)
    {
        $order = $this->orderModel->find($data['id']);


        if (!$order)
            return false;

        if (in_array($order->status, ['completed', 'cancelled'])) {
            return false;
        }


        DB::beginTransaction();

        try {


            if ($data['status'] === 'completed') {

                $expertOrders = ExpertOrder::where('order_id', $data['id'])
                    ->where('status', '!=', 'timedout')
                    ->get();

                if ($expertOrders->isNotEmpty()) {
                    $allCompleted = $expertOrders->every(function ($item) {
                        return $item->status === 'completed';
                    });

                    if ($allCompleted) {
                        $order->status = 'completed';
                        $order->save();
                    }
                    else{
                        return false;
                    }
                }

                $customerWallet = Wallet::where('walletable_id', $order->customer_id)
                    ->where('walletable_type', 'App\Models\Customer')
                    ->first();
                $customerWallet->decrement('frozen_balance', $order->order_amount - $order->discount);


                //create admin income record
                $incomeAmount = ($order->order_amount - $order->discount) * 0.10;

                Income::create([
                    'order_id' => $order->id,
                    'income_amount' => $incomeAmount,
                ]);

                $adminWallet = Wallet::firstOrCreate(
                    [
                        'walletable_id' => 4,
                        'walletable_type' => \App\Models\User::class,
                    ],
                    [
                        'walletable_id' => 4,
                        'walletable_type' => \App\Models\User::class,
                        'balance' => 0,
                    ]
                );


                $adminWallet->increment('balance', $incomeAmount);


                // Create vendor income record
                if ($order->order_type === 'Prepaid') {

                    $orderPackages = OrderPackage::where('order_id', $order->id)->get();
                    $orderExpertsAndVendors = ExpertOrder::where('order_id', $order->id)
                        ->where('status', '!=', 'timedout')
                        ->get();

                    $categoryPayables = [];

                    foreach ($orderPackages as $package) {
                        $net = $package->price - $package->discount;
                        $categoryId = $package->category_id;

                        if (!isset($categoryPayables[$categoryId])) {
                            $categoryPayables[$categoryId] = 0;
                        }

                        $categoryPayables[$categoryId] += $net;
                    }


                    $vendorAmounts = [];

                    foreach ($orderExpertsAndVendors as $item) {
                        $vendorId = $item->vendor_id;
                        $categoryId = $item->category_id;

                        if (!isset($categoryPayables[$categoryId])) {
                            continue;
                        }

                        $share = $categoryPayables[$categoryId];

                        if (!isset($vendorAmounts[$vendorId])) {
                            $vendorAmounts[$vendorId] = [];
                        }

                        if (!isset($vendorAmounts[$vendorId][$categoryId])) {
                            $vendorAmounts[$vendorId][$categoryId] = 0;
                        }

                        $vendorAmounts[$vendorId][$categoryId] += $share;
                    }


                    foreach ($vendorAmounts as $vendorId => $categories) {
                        // Create VendorIncome
                        foreach ($categories as $categoryId => $amount) {
                            // Create VendorIncome
                            VendorIncome::create([
                                'order_id' => $order->id,
                                'vendor_id' => $vendorId,
                                'category_id' => $categoryId,
                                'income_amount' => $amount,
                            ]);

                            // Ensure the vendor's wallet exists
                            $wallet = Wallet::firstOrCreate(
                                [
                                    'walletable_id' => $vendorId,
                                    'walletable_type' => \App\Models\Vendor::class,
                                ],
                                [
                                    'walletable_id' => $vendorId,
                                    'walletable_type' => \App\Models\Vendor::class,
                                    'balance' => 0,
                                ]
                            );


                            // Increment the wallet balance
                            $wallet->increment('balance', $amount);
                        }



                    }

                }



            }

            if ($data['status'] === 'cancelled') {
                OrderPackage::where('order_id', $order->id)->delete();
                $order->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e);
            return false;
        }
    }



    public function destroy($id)
    {
        $query = $this->orderModel->query();
        $order = $query->find($id);
        if ($order) {
            return $order->delete();
        }
        return false;
    }




}
