<?php

namespace App\Services\Vendor;

use App\Helpers\ImageHelper;
use App\Interfaces\Vendor\OrderServiceInterface;
use App\Models\ExpertOrder;
use App\Models\Order;
use App\Models\VendorIncome;

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

    public function vendorIncome($id){
$categories = ExpertOrder::where('order_id', $id)
    ->where('vendor_id', auth()->user()->id)
    ->where('status', '!=', 'timedout')
    ->pluck('category_id')
    ->toArray();

$vendorIncome = VendorIncome::where('order_id', $id)
    ->where('vendor_id', auth()->user()->id)
    ->whereIn('category_id', $categories)
    ->get();

        return $vendorIncome;
    }



     public function orderDetail($id)
    {
        $order  = $this->expertOrderModel->where('order_id', "=", $id)->where('status', "!=", 'timedout')->with(['order.customer', 'order.orderPackages.categoryPackage', 'order.orderPackages.category'])->get();
        return $order;
    }

    public function assignExpert($expertOrderId, $expertId){
        $expertOrder = $this->expertOrderModel->find($expertOrderId);
        $expertOrder->expert_id = $expertId;
        $expertOrder->status = 'assigned';
        $expertOrder->save();
        return $expertOrder;

    }

    public function rescheduleOrder($expertOrderId, $datetime)
{

    $carbonDate = \Carbon\Carbon::parse($datetime);

    $date = $carbonDate->toDateString();
    $time = $carbonDate->toTimeString();

    $expertOrder = $this->expertOrderModel->find($expertOrderId);
    if (!$expertOrder) {
        return response()->json(['message' => 'Order not found'], 404);
    }
    $expertOrder->date = $date;
    $expertOrder->time = $time;
    $expertOrder->status = 'rescheduled';
    $expertOrder->save();

    return $expertOrder;
}





}
