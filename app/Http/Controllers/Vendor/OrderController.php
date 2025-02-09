<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Interfaces\Vendor\OrderServiceInterface;

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

    public function allOrder(Request $request, $id){
        $search = $request->input('searchQuery');
        $orders = $this->orderService->allOrder($search, $id);
return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $orders
        ]);



    }

    public function orderDetail($id){
        $data = $this->orderService->orderDetail($id);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function assignExpert(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:expert_orders,id',
            'expert_id' => 'required|exists:experts,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ]);
        }

        $data = $this->orderService->assignExpert($request->id, $request->expert_id);

        return response()->json([
            'status' => 200,
            'message' => 'Expert assigned successfully',
            'data' => $data
        ]);
    }










}
