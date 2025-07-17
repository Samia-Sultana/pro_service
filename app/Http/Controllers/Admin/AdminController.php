<?php

namespace App\Http\Controllers\Admin;

use Password;
use Validator;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Admin\AdminServiceInterface;


class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminServiceInterface $adminService){
        $this->adminService = $adminService;
    }



    public function index(Request $request){

        $search = $request->input('searchQuery');

        $data = $this->adminService->index($search);
        $roles = Role::all();

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data,
            'roles' => $roles

        ]);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:225',
            'email' => 'required|email|unique:users,email',
            'roleId' => 'required|exists:roles,id',
            'password' => [
                'required',
                'string',
                'min:6',
            ],
        ]);
        if ($validator->fails()) {
    return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors()
    ], 422);
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

    public function passwordEmail(Request $request){
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
        $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        }



}
