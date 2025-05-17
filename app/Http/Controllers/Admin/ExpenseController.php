<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\ExpenseServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseServiceInterface $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index(Request $request)
    {
        $search = $request->input('searchQuery');
        $data = $this->expenseService->index($search);

        return response()->json([
            'status' => 200,
            'message' => 'Expenses retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'expense_type_id' => 'required|exists:expense_types,id',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
            'image' => [
                'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }

        $expense = $this->expenseService->store($validator->validated());

        return response()->json([
            'status' => 200,
            'message' => 'Expense created successfully',
            'data' => $expense
        ]);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:expenses,id',
            'title' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'expense_type_id' => 'required|exists:expense_types,id',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
            'image' => [
                'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }

        $expense = $this->expenseService->edit($validator->validated());

        return response()->json([
            'status' => 200,
            'message' => 'Expense updated successfully',
            'data' => $expense
        ]);
    }

    public function expenseDetail($id)
    {
        $data = $this->expenseService->expenseDetail($id);

        return response()->json([
            'status' => 200,
            'message' => 'Expense detail retrieved successfully',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $deleted = $this->expenseService->destroy($id);

        if ($deleted) {
            return response()->json([
                'status' => 200,
                'message' => 'Expense deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Expense not found'
            ], 404);
        }
    }
}
