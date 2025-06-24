<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Interfaces\Vendor\VendorIncomeServiceInterface;
use Illuminate\Http\Request;

class VendorIncomeController extends Controller
{
    protected $vendorIncomeService;

    public function __construct(VendorIncomeServiceInterface $vendorIncomeService)
    {
        $this->vendorIncomeService = $vendorIncomeService;
    }
    public function index(Request $request, $id)
    {

        $search = $request->input('searchQuery');
        $data = $this->vendorIncomeService->index($search, $id);

        return response()->json([
            'status' => 200,
            'message' => 'Income data retrieved successfully',
            'data' => $data
        ]);
    }
}
