<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CustomerWalletController extends Controller
{
    public function index(){

    }
    public function addMoney(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        $customer = Customer::findOrFail($id);
        $wallet = $customer->wallet;

        if (!$wallet) {
            $wallet = $customer->wallet()->create(['balance' => 0]);
        }

        $wallet->balance += $request->amount;
        $wallet->save();

        // Transaction::create([
        //     'type' => 'deposit',
        //     'amount' => $request->amount,
        //     'receiver_wallet_id' => $wallet->id,
        //     'description' => 'Wallet deposit by customer',
        //     'status' => 'completed',
        // ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Money added to wallet successfully',
            'wallet' => $wallet,
        ]);
    }

    public function withdraw()
    {
    }

    public function sendMoney()
    {
    }
}
