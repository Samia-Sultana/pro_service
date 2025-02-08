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
        info($id);
        $query  = $this->expertOrderModel->where('vendor_id', "=", $id)->with('order.customer');
        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        return $query->paginate(10);
     }

     public function orderDetail($id)
    {

        $order  = $this->orderModel->with(['orderPackages.category','orderPackages.categoryPackage'])->where('id', '=', $id)->first();
        return $order;

    }




}
