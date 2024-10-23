<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\RoleServiceInterface;
use Illuminate\Http\Request;
use Validator;

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
        info('hiiii');
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
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


}
