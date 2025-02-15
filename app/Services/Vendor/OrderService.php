<?php

namespace App\Services\Vendor;

use App\Helpers\ImageHelper;
use App\Interfaces\Vendor\OrderServiceInterface;
use App\Models\ExpertOrder;
use App\Models\Order;

class OrderService implements OrderServiceInterface
{
    private ExpertOrder $expertOrderModel;
    private Order $orderModel;
    public function __construct(ExpertOrder $expertOrderModel, Order $orderModel)
    {
        $this->expertOrderModel = $expertOrderModel;
        $this->orderModel = $orderModel;
    }

    public function allOrder($search = null, $id){
        $query  = $this->expertOrderModel->where('vendor_id', "=", $id)->where('status', "!=", 'timedout')->with(['order.customer', 'order.orderPackages.categoryPackage']);

        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        return $query->paginate(10);
     }

     public function orderDetail($id)
    {
        $order  = $this->expertOrderModel->where('order_id', "=", $id)->with(['order.customer', 'order.orderPackages.categoryPackage', 'order.orderPackages.category'])->get();
        return $order;
    }

    public function assignExpert($expertOrderId, $expertId){
        $expertOrder = $this->expertOrderModel->find($expertOrderId);
        $expertOrder->expert_id = $expertId;
        $expertOrder->status = 'assigned';
        $expertOrder->save();
        return $expertOrder;

    }




}
