<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\OrderPackageInterface;
use App\Interfaces\Expert\OrderServiceInterface;
use App\Models\Income;
use App\Models\VendorIncome;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    protected $orderService;


    public function __construct(OrderServiceInterface $orderService){
        $this->orderService = $orderService;

    }

    public function allOrder($id){
        $orders = $this->orderService->allOrder($id);
        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $orders
        ]);



    }

    public function currentOrder($id){
        $order = $this->orderService->currentOrder($id);
        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $order
        ]);


    }

    public function orderDetail(){

    }

    public function updateStatus(Request $request)
{
    $validated = Validator::make($request->all(), [
        'id' => 'required|exists:orders,id',
        'category_id' => 'required|exists:categories,id',
        'expert_id' => 'required|exists:experts,id',
        'status' => 'required|in:started,completed',
    ]);

    if ($validated->fails()) {
        return response()->json([
            'status' => 422,
            'message' => 'Invalid data',
            'errors' => $validated->errors()
        ]);
    }

    $data = $validated->validated();

    try {
        DB::beginTransaction();

        $success = $this->orderService->updateOrderStatus($data);


        if (!$success) {
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => 'Could not update status']);
        }

        DB::commit();
        return response()->json(['status' => 200, 'message' => 'Status updated successfully']);
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error($e);
        return response()->json(['status' => 500, 'message' => 'Internal server error']);
    }
}







}
