<?php

namespace App\Services\Expert;

use App\Helpers\ImageHelper;
use App\Interfaces\Expert\OrderServiceInterface;
use App\Models\ExpertOrder;

class OrderService implements OrderServiceInterface
{
    private ExpertOrder $expertOrderModel;
    public function __construct(ExpertOrder $expertOrderModel)
    {
        $this->expertOrderModel = $expertOrderModel;
    }
    public function allOrder($id){
        return $this->expertOrderModel->where('expert_id',$id)->get();
    }




}
