<?php

namespace App\Interfaces\Vendor;

interface OrderServiceInterface
{
    public function allOrder($search = null,int $id);
    public function orderDetail(int $id);

    public function vendorIncome(int $id);

    public function assignExpert($expertOrderId, $expertId);
    public function rescheduleOrder($expertOrderId, $date, $slot);




}
