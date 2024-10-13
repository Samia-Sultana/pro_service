<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\AdminServiceInterface;
use Illuminate\Http\Request;
use Validator;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminServiceInterface $adminService){
        $this->adminService = $adminService;
    }

    public function index(){
        $data = $this->adminService->index();

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:8',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/'
            ],
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }
        $data = $this->adminService->store($validator->validated());
        return response()->json([
            'status' => 200,
            'message' => 'Admin created successfully',
            'data' => $data
        ]);

    }

    public function adminDetail($id){
        $data = $this->adminService->adminDetail($id);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    // public function edit(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'string|max:8',
    //         'email' => 'email|unique:users,email',
    //     ]);
    //     if($validator->fails()){
    //         return response()->json([
    //             'status' => 422,
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors()
    //         ]);
    //     }
    //     $data = $this->adminService->edit($validator->validated());
    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Data updated successfully',
    //         'data' => $data
    //     ]);

    // }
    public function destroy($id){
        $deleted = $this->adminService->destroy($id);
        if ($deleted) {
            return response()->json([
            'status' => 200,
            'message' => 'Admin deleted successfully',
            ]);
        } else {
            return response()->json([
            'status' => 404,
            'message' => 'Admin not found',
            ], 404);
        }

    }

}
