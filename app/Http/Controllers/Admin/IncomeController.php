<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Models\Income;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Interfaces\Admin\IncomeServiceInterface;

class IncomeController extends Controller
{
    protected $incomeService;

    public function __construct(IncomeServiceInterface $incomeService)
    {
        $this->incomeService = $incomeService;
    }

    public function index(Request $request)
    {
        $search = $request->input('searchQuery');
        $data = $this->incomeService->index($search);

        return response()->json([
            'status' => 200,
            'message' => 'Income data retrieved successfully',
            'data' => $data
        ]);
    }


    public function incomeStatusUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:incomes,id',
            'status' => 'required|string|in:complete',
        ]);

        $income = Income::findOrFail($request->id);

        $income->status = $request->status;
        $income->save();

        $adminWallet = Wallet::where('walletable_id', '4')
            ->where('walletable_type', 'App\Models\User')
            ->first();
        if ($adminWallet) {
            $adminWallet->increment('balance', $income->income_amount);
        }

    }

    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'income_amount' => 'required|numeric|min:0',
        //     'remarks' => 'nullable|string|max:255',
        //     'date' => 'required|date',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 422,
        //         'message' => 'Validation failed',
        //         'errors' => $validator->errors()
        //     ]);
        // }

        // $incomeData = $validator->validated();

        // $income = $this->incomeService->store($incomeData);

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Income created successfully',
        //     'data' => $income
        // ]);
    }

    public function edit(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'id' => 'required|exists:incomes,id',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 422,
        //         'message' => 'Validation failed',
        //         'errors' => $validator->errors()
        //     ]);
        // }

        // $income = $this->incomeService->edit($validator->validated());

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Income updated successfully',
        //     'data' => $income
        // ]);
    }

    public function incomeDetail($id)
    {
        $data = $this->incomeService->incomeDetail($id);

        return response()->json([
            'status' => 200,
            'message' => 'Income detail retrieved successfully',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        // $deleted = $this->incomeService->destroy($id);

        // if ($deleted) {
        //     return response()->json([
        //         'status' => 200,
        //         'message' => 'Income deleted successfully',
        //     ]);
        // } else {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'Income not found',
        //     ], 404);
        // }
    }
}
