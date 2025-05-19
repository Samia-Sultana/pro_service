<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\OrderPackageInterface;
use App\Interfaces\Admin\OrderServiceInterface;
use DB;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderPackageService;

    public function __construct(OrderServiceInterface $orderService, OrderPackageInterface $orderPackageService){
        $this->orderService = $orderService;
        $this->orderPackageService = $orderPackageService;

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
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'category_package_ids' => 'required|array',
            'category_package_ids.*' => 'exists:category_packages,id',
            'customer_id' => 'required|exists:customers,id',
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

        DB::beginTransaction();
        try {
            $order = $this->orderService->store($orderData);
            if($order){
                $order_package = $this->orderPackageService->store($orderData, $order->id);
                DB::commit();
            }

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        return response()->json([
            'status' => 200,
            'message' => 'order created successfully',
            'data' => [
                'order' => $order,
                // 'order_package' => $order_package
            ],
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
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'services' => 'required|array',
            'services.*.order_id' => 'required|integer|exists:orders,id',
            'services.*.category_id' => 'required|integer|exists:categories,id',
            'services.*.category_package_id' => 'required|integer|exists:category_packages,id',
            'services.*.price' => 'required|numeric',
            'services.*.discount' => 'nullable|numeric',

        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }
        $orderData = $validator->validated();
        try {
        DB::beginTransaction();
        try {
            $this->orderPackageService->update($orderData);
            DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        return response()->json([
            'status' => 200,
            'message' => 'order created successfully',
            'data' => [
                // 'order' => $order,
                // 'order_package' => $order_package
            ],
        ]);

    } catch (\Exception $e) {
        return response()->json(['message' => 'Error updating order', 'error' => $e->getMessage()], 500);
    }


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

    public function updateOrderStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,processing,canceled,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }

        $orderData = $validator->validated();
        $order = $this->orderService->updateOrderStatus( $orderData);

        return response()->json([
            'status' => 200,
            'message' => 'Order status updated successfully',
            'data' => $order
        ]);


    }

}
