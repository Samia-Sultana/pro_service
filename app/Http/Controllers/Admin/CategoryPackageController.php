<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\CategoryPackageServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

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

    public function allPackage(){

        $data = $this->packageService->allPackage();
        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:category_packages,name',
            'tag' => 'required|string|max:255|unique:category_packages,tag',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'required|string|max:255',
            'image' => [
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
            'price' => 'required',
            'discount' => 'nullable',
        ]);

        if ($validator->fails()) {
    return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors()
    ], 422);
        }

        $packageData = $validator->validated();
        $package = $this->packageService->store($packageData);
        return response()->json([
            'status' => 200,
            'message' => 'package created successfully',
            'data' => $package
        ]);

    }

     public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:category_packages,id',
            'name' => [
                'required', 'string', Rule::unique('category_packages', 'name')->ignore($request->id),
            ],
            'tag' => [
                'required', 'string', Rule::unique('category_packages', 'tag')->ignore($request->id),
            ],
            'category_id' => 'nullable|exists:categories,id',


            'description' =>'required|string|max:255',

            'image' => [
                'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048',
            ],
            'price' => 'required',
            'discount' => 'nullable',
        ]);

         if ($validator->fails()) {
    return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors()
    ], 422);
        }

        $packageData = $validator->validated();
        $package = $this->packageService->edit($packageData);

        return response()->json([
            'status' => 200,
            'message' => 'package updated successfully',
            'data' => $package
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
