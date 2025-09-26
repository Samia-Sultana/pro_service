<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use App\Models\Order;
use App\Models\ExpertOrder;
use App\Models\OrderPackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessCategoryWiseOrderJob;
use App\Interfaces\Admin\OrderPackageInterface;
use App\Interfaces\Admin\OrderServiceInterface;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderPackageService;

    public function __construct(OrderServiceInterface $orderService, OrderPackageInterface $orderPackageService)
    {
        $this->orderService = $orderService;
        $this->orderPackageService = $orderPackageService;

    }

    public function index(Request $request)
    {
        $search = $request->input('searchQuery');
        $data = $this->orderService->index($search);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'category_package_ids' => 'required|array',
            'category_package_ids.*' => 'exists:category_packages,id',
            'customer_id' => 'required|exists:customers,id',
            'order_type' => 'required|string|in:Prepaid,Postpaid',
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

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $orderData = $validator->validated();

        if ($orderData['order_type'] === 'Prepaid') {
            $customer = \App\Models\Customer::with('wallet')->find($orderData['customer_id']);

            if (!$customer || $customer->wallet == null) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Wallet not found for this customer. Please create wallet first',
                ]);
            }

            $amount = $orderData['order_amount'] - ($orderData['discount'] ?? 0);
            $walletBalance = $customer->wallet->balance;
            $frozenBalance = $customer->wallet->frozen_balance;
            $availableBalance = $walletBalance - $frozenBalance;

            if ($availableBalance < $amount) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Insufficient wallet balance. Please add funds to Customer account.',
                ]);
            }
        }

        DB::beginTransaction();

        try {
            $order = $this->orderService->store($orderData);

            if ($order) {
                $this->orderPackageService->store($orderData, $order->id);

                if ($orderData['order_type'] === 'Prepaid') {
                    $customer->wallet->increment('frozen_balance', $amount);
                }

                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([
            'status' => 200,
            'message' => 'Order created successfully',
            'data' => [
                'order' => $order,
            ],
            'redirect_url' => 'order-list'
        ]);
    }


    public function orderDetail($id)
    {
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

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
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

    public function destroy($id)
    {
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
        $validated = $request->validate([
            'id' => 'required|integer|exists:orders,id',
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $result = $this->orderService->updateOrderStatus($validated);


        return $result
            ? response()->json(['message' => 'Status updated successfully'])
            : response()->json(['message' => 'Failed to update status'], 500);


    }

    public function addService(Request $request)
    {

        $services = $request->all();
        $validator = Validator::make($services, [
            '*.order_id' => 'required|exists:orders,id',
            '*.category_id' => 'required|exists:categories,id',
            '*.category_package_id' =>
                'required|exists:category_packages,id'
            ,
            '*.price' => 'required',
            '*.discount' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedServices = $validator->validated();

        $order = Order::find($validatedServices[0]['order_id']);

        foreach ($validatedServices as $service) {

            $exists = OrderPackage::where([
                'order_id' => $service['order_id'],
                'category_id' => $service['category_id'],
                'category_package_id' => $service['category_package_id']
            ])->exists();

            if (!$exists) {
                try {
                    $orderPackage = OrderPackage::create([
                        'order_id' => $service['order_id'],
                        'category_id' => $service['category_id'],
                        'category_package_id' => $service['category_package_id'],
                        'price' => $service['price'],
                        'discount' => $service['discount'],

                    ]);

                    $expertOrders = ExpertOrder::where([
                        'order_id' => $service['order_id'],
                        'category_id' => $service['category_id']
                    ])->where('status', '!=', 'timedout')->get();

                    $expertCount = $expertOrders->count();
                    if ($expertOrders->count() == 0) {
                        ProcessCategoryWiseOrderJob::dispatch($service['order_id'], $order->slot, $order->date, $service['category_id']);

                    }



                } catch (\Exception $e) {
                    $results[] = [
                        'status' => 'error',
                        'data' => $service,
                        'message' => 'Failed to create service package: ' . $e->getMessage()
                    ];
                }
            } else {
                $results[] = [
                    'status' => 'exists',
                    'data' => $service,
                    'message' => 'Service package already exists'
                ];
            }
        }
        $results = [
            'message' => 'Services processed successfully',
        ];
    }
}
