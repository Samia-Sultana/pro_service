<?php

namespace App\Services\Expert;

use App\Models\Order;
use App\Models\Income;
use App\Models\ExpertOrder;
use App\Helpers\ImageHelper;
use App\Models\OrderPackage;
use App\Models\VendorIncome;
use App\Interfaces\Expert\OrderServiceInterface;

class OrderService implements OrderServiceInterface
{
    private ExpertOrder $expertOrderModel;
    public function __construct(ExpertOrder $expertOrderModel)
    {
        $this->expertOrderModel = $expertOrderModel;
    }
    public function allOrder($id)
    {
        $allOrders = $this->expertOrderModel
            ->where('expert_id', $id)
            ->with(['order.customer', 'order.orderPackages.categoryPackage'])
            ->get();
        return $allOrders;
    }

    public function currentOrder($id)
    {
        $currentOrder = $this->expertOrderModel
            ->where('expert_id', $id)
            ->where('status', 'started')
            ->with(['order.customer', 'order.orderPackages.categoryPackage'])
            ->get();
        return $currentOrder;
    }

    public function updateOrderStatus($data)
    {
        $expertOrder = $this->expertOrderModel->where('order_id', $data['id'])
            ->where('category_id', $data['category_id'])
            ->where('expert_id', $data['expert_id'])
            ->where('status', '!=', 'timedout')
            ->first();


        if ($expertOrder) {
            $expertOrder->status = $data['status'];
            $expertOrder->save();
        } else {
            return false;
        }

        if ($data['status'] === 'completed') {
            $order = Order::find($data['id']);
            if (!$order) {
                info(message: 'Order not found for ID: ' . $data['id']);
                return false;
            }

            // $incomeAmount = ($order->order_amount - $order->discount) * 0.10;
            // Income::create([
            //     'order_id' => $order->id,
            //     'income_amount' => $incomeAmount,
            //     'status' => 'pending',
            // ]);

            if($order->order_type === 'Postpaid'){
                $orderPackages = OrderPackage::where('order_id', $order->id)->get();
                $categoryPayables = [];

            foreach ($orderPackages as $package) {
                $net = $package->price - $package->discount;
                $categoryId = $package->category_id;

                if (!isset($categoryPayables[$categoryId])) {
                    $categoryPayables[$categoryId] = 0;
                }

                $categoryPayables[$categoryId] += $net;
            }

            $vendorId = $expertOrder->vendor_id;
            $categoryId = $expertOrder->category_id;
            $share = $categoryPayables[$categoryId] ?? 0;

            VendorIncome::create([
                'order_id' => $order->id,
                'vendor_id' => $vendorId,
                'income_amount' => $share,
                'status' => 'pending',
                'category_id' => $categoryId,
            ]);
            }

            elseif($order->order_type === 'Prepaid'){
                //notification for admin
            }
        }

        return true;
    }






}
