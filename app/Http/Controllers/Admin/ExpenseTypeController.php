<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\ExpenseTypeServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class ExpenseTypeController extends Controller
{
    protected $expenseTypeService;

    public function __construct(ExpenseTypeServiceInterface $expenseTypeService)
    {
        $this->expenseTypeService = $expenseTypeService;
    }

    public function index(Request $request)
    {
        $search = $request->input('searchQuery');
        $data = $this->expenseTypeService->index($search);

        return response()->json([
            'status' => 200,
            'message' => 'Expense types retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:expense_types,name',
            'status' => 'nullable|string',
            'description' => 'nullable|string|max:255'
        ]);

       if ($validator->fails()) {
    return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors()
    ], 422);
        }

        $expenseType = $this->expenseTypeService->store($validator->validated());

        return response()->json([
            'status' => 200,
            'message' => 'Expense type created successfully',
            'data' => $expenseType
        ]);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:expense_types,id',
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('expense_types', 'name')->ignore($request->id)
            ],
            'status' => 'nullable|string',
            'description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
    return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors()
    ], 422);
        }

        $expenseType = $this->expenseTypeService->edit($validator->validated());

        return response()->json([
            'status' => 200,
            'message' => 'Expense type updated successfully',
            'data' => $expenseType
        ]);
    }

    public function expenseTypeDetail($id)
    {
        $data = $this->expenseTypeService->expenseTypeDetail($id);

        return response()->json([
            'status' => 200,
            'message' => 'Expense type details retrieved successfully',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $deleted = $this->expenseTypeService->destroy($id);

        if ($deleted) {
            return response()->json([
                'status' => 200,
                'message' => 'Expense type deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Expense type not found'
            ], 404);
        }
    }
}
