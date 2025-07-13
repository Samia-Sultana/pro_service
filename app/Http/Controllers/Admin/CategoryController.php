<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\CategoryServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class CategoryController extends Controller
{
    public function __construct(CategoryServiceInterface $categoryService){
        $this->categoryService = $categoryService;
    }

    public function index(Request $request){

        $search = $request->input('searchQuery');

        $data = $this->categoryService->index($search);
        info($data);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:categories,name',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'required|string|max:255',
            'image' => [
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
            'card' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
    return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors()
    ], 422);
        }

        $categoryData = $validator->validated();
        $category = $this->categoryService->store($categoryData);
        return response()->json([
            'status' => 200,
            'message' => 'category created successfully',
            'data' => $category
        ]);

    }


    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id',
            'name' => [
                'required', 'string', Rule::unique('categories', 'name')->ignore($request->id),
            ],
            'slug' => [
                'required', 'string', Rule::unique('categories', 'slug')->ignore($request->id),
            ],
            'parent_id' => 'nullable|exists:categories,id',


            'description' =>'required|string|max:255',

            'image' => [
                'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048',
            ],
            'card' => 'required|in:0,1'
        ]);

         if ($validator->fails()) {
    return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors()
    ], 422);
        }

        $categoryData = $validator->validated();
        $category = $this->categoryService->edit($categoryData);

        return response()->json([
            'status' => 200,
            'message' => 'category updated successfully',
            'data' => $category
        ]);
    }

    public function categoryDetail($id){
        $data = $this->categoryService->categoryDetail($id);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function destroy($id){
        $deleted = $this->categoryService->destroy($id);
        if ($deleted) {
            return response()->json([
            'status' => 200,
            'message' => 'category deleted successfully',
            ]);
        } else {
            return response()->json([
            'status' => 404,
            'message' => 'category not found',
            ], 404);
        }

    }
}
