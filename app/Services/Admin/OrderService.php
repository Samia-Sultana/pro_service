<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Interfaces\Admin\OrderServiceInterface;
use App\Models\Income;
use App\Models\Order;
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
        $query  = $this->orderModel->with('customer');
        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        return $query->paginate(10);
    }
    public function store(array $data)
    {
        $query  = $this->orderModel->query();
        $order = $query->create([
        'customer_id' => $data['customer_id'],
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

        $order  = $this->orderModel->with(['orderPackages.category','orderPackages.categoryPackage'])->where('id', '=', $id)->first();
        info($order);
        return $order;

    }

    public function updateOrderStatus($orderData){
        $query = $this->orderModel->query();
    $order = $query->find($orderData['id']);

    if (!$order) {
        return false;
    }

    return DB::transaction(function () use ($order, $orderData) {
        $newStatus = $orderData['status'];

        if ($newStatus === 'completed') {
            $order->status = 'completed';
            $order->save();

            if (!Income::where('order_id', $order->id)->exists()) {
                $incomeAmount = $order->order_amount * 0.10;

                Income::create([
                    'order_id' => $order->id,
                    'income_amount' => $incomeAmount,
                ]);
            }

        } elseif ($newStatus === 'cancelled') {
            DB::table('order_packages')->where('order_id', $order->id)->delete();
            $order->delete();

        } else {
            $order->status = $newStatus;
            $order->save();
        }

        return true;
    });
    }



    public function destroy($id)
    {
        $query  = $this->orderModel->query();
        $order = $query->find($id);
        if ($order) {
            return $order->delete();
        }
        return false;
    }




}
