<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\CategoryPackageServiceInterface;
use Illuminate\Http\Request;

class CategoryPackageController extends Controller
{
    protected $packageService;

    public function __construct(CategoryPackageServiceInterface $packageService){
        $this->packageService = $packageService;
    }

    public function index(Request $request){
        // $search = $request->input('searchQuery');
        $categories = $request->input('categories');
        if (!is_array($categories)) {
            $categories = explode(',', $categories);
        }

        $data = $this->packageService->index($categories);
        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function packageDetail($id){
        $data = $this->packageService->packageDetail($id);
        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }
}
