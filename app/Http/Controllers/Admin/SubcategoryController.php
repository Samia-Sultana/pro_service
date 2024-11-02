<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\SubcategoryServiceInterface;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function __construct(SubcategoryServiceInterface $subcategoryService){
        $this->subcategoryService = $subcategoryService;
    }

    public function index(Request $request){

        $search = $request->input('searchQuery');

        $data = $this->subcategoryService->index($search);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

}
