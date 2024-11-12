<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\SubcategoryServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

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

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => [
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
            'card' => 'required|in:0,1',
            'category_id' => 'required|exists:categories,id',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }
        $categoryData = $validator->validated();
        $subcategory = $this->subcategoryService->store($categoryData);
        return response()->json([
            'status' => 200,
            'message' => 'subcategory created successfully',
            'data' => $subcategory
        ]);

    }


    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:subcategories,id',
            'name' => [
                'required', 'string', Rule::unique('categories', 'name')->ignore($request->id),
            ],
            'slug' => [
                'required', 'string', Rule::unique('categories', 'slug')->ignore($request->id),
            ],

            'description' =>'required|string|max:255',

            'image' => [
                'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048',
            ],
            'card' => 'required|in:0,1',
            'category_id' => 'required|exists:categories,id',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }
        $categoryData = $validator->validated();
        $subcategory = $this->subcategoryService->edit($categoryData);

        return response()->json([
            'status' => 200,
            'message' => 'subcategory updated successfully',
            'data' => $subcategory
        ]);
    }

    public function subcategoryDetail($id){
        $data = $this->subcategoryService->categoryDetail($id);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function destroy($id){
        $deleted = $this->subcategoryService->destroy($id);
        if ($deleted) {
            return response()->json([
            'status' => 200,
            'message' => 'subcategory deleted successfully',
            ]);
        } else {
            return response()->json([
            'status' => 404,
            'message' => 'subcategory not found',
            ], 404);
        }

    }

}
