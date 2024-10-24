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

    public function index(){
        $data = $this->vendorService->index();

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
            'service_status' => 'required|in:pending,verified,blocked',
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

        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
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
            'id' => 'required|exists:roles,id',
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($request->id),
            ],
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }
        $data = $this->vendorService->edit($validator->validated());
        return response()->json([
            'status' => 200,
            'message' => 'role updated successfully',
            'data' => $data
        ]);
    }

    public function destroy($id){
        $deleted = $this->vendorService->destroy($id);
        if ($deleted) {
            return response()->json([
            'status' => 200,
            'message' => 'role deleted successfully',
            ]);
        } else {
            return response()->json([
            'status' => 404,
            'message' => 'role not found',
            ], 404);
        }

    }
}
