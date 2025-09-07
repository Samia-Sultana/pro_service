<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\VendorServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class VendorController extends Controller
{
    protected $vendorService;

    public function __construct(VendorServiceInterface $vendorService){
        $this->vendorService = $vendorService;
    }

    public function index(Request $request){
        $search = $request->input('searchQuery');
        $data = $this->vendorService->index($search);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:8',
            'company_name' => 'required|string|max:20',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|numeric|unique:vendors,phone',
            'nid_number' => 'required|numeric|unique:vendors,nid_number',
            'service_status' => 'required|in:pending,active,blocked',
            'login_status' => 'required|in:pending,active,blocked',
            'vendor_photo' => [
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
            'nid_photo' => [
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
            'password' => 'nullable',

        ]);

       if ($validator->fails()) {
    return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors()
    ], 422);
        }
        $data = $this->vendorService->store($validator->validated());
        return response()->json([
            'status' => 200,
            'message' => 'role created successfully',
            'data' => $data
        ]);

    }

    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:vendors,id',
            'name' => ['required', 'string', 'max:8'],
            'company_name' => [
                'required', 'string', 'max:20',
                Rule::unique('vendors', 'company_name')->ignore($request->id),
            ],
            'email' => [
                'required', 'email', Rule::unique('vendors', 'email')->ignore($request->id),
            ],
            'phone' => [
                'required', 'numeric', Rule::unique('vendors', 'phone')->ignore($request->id),
            ],
            'nid_number' => [
                'required', 'numeric', Rule::unique('vendors', 'nid_number')->ignore($request->id),
            ],
            'service_status' => 'required|in:pending,active,blocked',
            'login_status' => 'required|in:pending,active,blocked',
            'vendor_photo' => [
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
        $data = $this->vendorService->edit($validator->validated());
        return response()->json([
            'status' => 200,
            'message' => 'role updated successfully',
            'data' => $data
        ]);
    }
    public function vendorDetail($id){
        $data = $this->vendorService->vendorDetail($id);
        info('kijhsggha');

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function destroy($id){
        $deleted = $this->vendorService->destroy($id);
        if ($deleted) {
            return response()->json([
            'status' => 200,
            'message' => 'vendor deleted successfully',
            ]);
        } else {
            return response()->json([
            'status' => 404,
            'message' => 'vendor not found',
            ], 404);
        }

    }
}
