<?php

namespace App\Services\Vendor;

use App\Helpers\ImageHelper;
use App\Interfaces\Vendor\OrderServiceInterface;
use App\Models\ExpertOrder;
use App\Models\Order;

class OrderService implements OrderServiceInterface
{
    private ExpertOrder $expertOrderModel;
    public function __construct(ExpertOrder $expertOrderModel)
    {
        $this->expertOrderModel = $expertOrderModel;
    }

    public function allOrder($id){
        $allOrders = $this->expertOrderModel
    ->where('vendor_id', $id)
    ->with('order.customer')
    ->get();
    return $allOrders;
     }




}
