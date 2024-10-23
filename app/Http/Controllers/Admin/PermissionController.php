<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\PermissionServiceInterface;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionServiceInterface $permissionService){
        $this->permissionService = $permissionService;
    }

    public function index(){
        $data = $this->permissionService->index();

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }


    public function destroy($id){
        $deleted = $this->permissionService->destroy($id);
        if ($deleted) {
            return response()->json([
            'status' => 200,
            'message' => 'permission deleted successfully',
            ]);
        } else {
            return response()->json([
            'status' => 404,
            'message' => 'permission not found',
            ], 404);
        }

    }

}
