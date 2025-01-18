<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\OrderServiceInterface;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderServiceInterface $orderService){
        $this->orderService = $orderService;
    }

    public function index(Request $request){
        $search = $request->input('searchQuery');
        $data = $this->orderService->index($search);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
    'category_id' => 'required|exists:categories,id',
    'customer_id' => 'required|exists:customers,id',
    'category_package_id' => 'required|exists:category_packages,id',
    'area' => 'required|string|max:255',
    'house_no' => 'required|string|max:255',
    'road_no' => 'required|string|max:255',
    'block' => 'required|string|max:255',
    'district' => 'required|string|max:255',
    'additional_info' => 'nullable|string|max:500',
    'order_amount' => 'required|numeric|min:0',
    'discount' => 'nullable|numeric|min:0',
    'cupon' => 'nullable|string|max:255',
    'description' => 'nullable|string|max:1000',
    'date' => 'required|date|after_or_equal:today',
    'slot' => 'required|string|max:255',
    'status' => 'nullable|string|in:pending,processing,canceled,completed',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }
        $orderData = $validator->validated();
        $order = $this->orderService->store($orderData);
        return response()->json([
            'status' => 200,
            'message' => 'order created successfully',
            'data' => $order,
        ]);

    }

    public function destroy($id){
        $deleted = $this->orderService->destroy($id);
        if ($deleted) {
            return response()->json([
            'status' => 200,
            'message' => 'order deleted successfully',
            ]);
        } else {
            return response()->json([
            'status' => 404,
            'message' => 'order not found',
            ], 404);
        }

    }

}
