<?php

namespace App\Interfaces\Expert;

interface OrderServiceInterface
{
    public function allOrder(int $id);
    public function currentOrder(int $id);

    public function updateOrderStatus($order);



}
