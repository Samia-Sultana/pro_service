<?php

namespace App\Services\Expert;

use App\Helpers\ImageHelper;
use App\Interfaces\Expert\OrderServiceInterface;
use App\Models\Order;

class OrderService implements OrderServiceInterface
{
    private Order $orderModel;
    public function __construct(Order $orderModel)
    {
        $this->orderModel = $orderModel;
    }




}
