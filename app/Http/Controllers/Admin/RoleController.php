<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\RoleServiceInterface;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;


class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleServiceInterface $roleService){
        $this->roleService = $roleService;
    }

    public function index(){
        $data = $this->roleService->index();

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            // 'permissions' => 'required|array',
            // 'permissions.*' => 'exists:permissions,id',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }
        $data = $this->roleService->store($validator->validated());
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
            // 'permissions' => 'required|array',
            // 'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }
        $data = $this->roleService->edit($validator->validated());
        return response()->json([
            'status' => 200,
            'message' => 'role updated successfully',
            'data' => $data
        ]);
    }

    public function destroy($id){
        $deleted = $this->roleService->destroy($id);
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
