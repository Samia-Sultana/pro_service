<?php

namespace App\Services\Expert;

use App\Helpers\ImageHelper;
use App\Interfaces\Expert\OrderServiceInterface;
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
    ->where('expert_id', $id)
    ->with(['order.customer', 'order.orderPackages.categoryPackage'])
    ->get();
    return $allOrders;
     }

    public function currentOrder($id){
        $currentOrder = $this->expertOrderModel
    ->where('expert_id', $id)
    ->where('status', 'started')
    ->with(['order.customer', 'order.orderPackages.categoryPackage'])
    ->get();
    return $currentOrder;
    }





}
