<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\CategoryServiceInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(CategoryServiceInterface $categoryService){
        $this->categoryService = $categoryService;
    }

    public function index(Request $request){

        $search = $request->input('searchQuery');

        $data = $this->categoryService->index($search);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }
}
