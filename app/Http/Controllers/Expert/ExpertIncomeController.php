<?php

namespace App\Http\Controllers\Expert;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Expert\ExpertIncomeServiceInterface;

class ExpertIncomeController extends Controller
{
    protected $expertIncomeService;

    public function __construct(ExpertIncomeServiceInterface $expertIncomeService)
    {
        $this->expertIncomeService = $expertIncomeService;
    }
    public function index(Request $request, $id)
    {

        $search = $request->input('searchQuery');
        $data = $this->expertIncomeService->index($search, $id);

        return response()->json([
            'status' => 200,
            'message' => 'Income data retrieved successfully',
            'data' => $data
        ]);
    }
}
