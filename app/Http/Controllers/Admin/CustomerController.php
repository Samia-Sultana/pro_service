<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\CustomerServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerServiceInterface $customerService){
        $this->customerService = $customerService;
    }

    public function index(Request $request){
        $search = $request->input('searchQuery');
        $data = $this->customerService->index($search);
        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|numeric|unique:customers,phone',
           'district' => 'required|string|max:100',
           'area' => 'required|string|max:100',
           'road_no' => 'required|string|max:100',
           'house_no' => 'required|string|max:100',
           'status' => 'required',

        ]);

        if ($validator->fails()) {
    return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors()
    ], 422);
        }
        $expertData = $validator->validated();


        $expert = $this->customerService->store($expertData);

        return response()->json([
            'status' => 200,
            'message' => 'expert created successfully',
            'data' => $expert
        ]);

    }


    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:experts,id',
            'name' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'vendor_id' => 'required|exists:vendors,id',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'subcategory_ids' => 'required|array',
            'subcategory_ids.*' => 'exists:subcategories,id',
            'email' => [
                'required', 'email', Rule::unique('experts', 'email')->ignore($request->id),
            ],
            'phone' => [
                'required', 'numeric', Rule::unique('experts', 'phone')->ignore($request->id),
            ],
            'nid_number' => [
                'required', 'numeric', Rule::unique('experts', 'nid_number')->ignore($request->id),
            ],

            'expert_photo' => [
                'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048',
            ],
            'nid_photo' => [
                'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048',
            ],
        ]);

        if ($validator->fails()) {
    return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors()
    ], 422);
        }
        $expertData = $validator->validated();
        $categoryIds = $expertData['category_ids'];
        $subcategoryIds = $expertData['subcategory_ids'];
        unset($expertData['category_ids'], $expertData['subcategory_ids']);

        $expert = $this->customerService->edit($validator->validated());
        $expert->categories()->sync($categoryIds);
        $expert->subcategories()->sync($subcategoryIds);
        return response()->json([
            'status' => 200,
            'message' => 'expert updated successfully',
            'data' => $expert
        ]);
    }

    public function customerDetail($id){
        $data = $this->customerService->customerDetail($id);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function customerOrders($id){
        $data = $this->customerService->customerOrders($id);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function destroy($id){
        $deleted = $this->customerService->destroy($id);
        if ($deleted) {
            return response()->json([
            'status' => 200,
            'message' => 'expert deleted successfully',
            ]);
        } else {
            return response()->json([
            'status' => 404,
            'message' => 'expert not found',
            ], 404);
        }

    }
}
