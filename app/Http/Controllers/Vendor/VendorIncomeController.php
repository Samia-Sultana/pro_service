<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Income;
use App\Models\Wallet;
use App\Models\VendorIncome;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Vendor\VendorIncomeServiceInterface;

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

    public function incomeStatusUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:vendor_incomes,id',
            'status' => 'required|string|in:complete',
        ]);

        $vendorIncome = VendorIncome::findOrFail($request->id);

        $vendorIncome->status = $request->status;
        $vendorIncome->save();

        $vendorWallet = Wallet::where('walletable_id', $vendorIncome->vendor_id)
            ->where('walletable_type', 'App\Models\Vendor')
            ->first();
        if ($vendorWallet) {
            $vendorWallet->increment('balance', $vendorIncome->income_amount);
        }

        $incomeAmount = $vendorIncome->income_amount * 0.10;

        Income::create(
    ['income_amount' => $incomeAmount,
    'order_id' => $vendorIncome->order_id,
    'status' => 'pending',
]);

    }
}
