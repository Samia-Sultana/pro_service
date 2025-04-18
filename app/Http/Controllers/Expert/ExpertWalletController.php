<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\Transaction;
use DB;
use Illuminate\Http\Request;

class ExpertWalletController extends Controller
{
    public function index(Request $request){
        $user = auth()->user();
        $wallet = $user->wallet;
        if (!$wallet) {

            return response()->json(['message' => 'User does not have a wallet'], 422);

        }

        return response()->json([
            'data' => $wallet->balance,
        ]);
    }


    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $senderWallet = $user->wallet;


        if ($senderWallet->balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance'], 422);
        }

        DB::transaction(function () use ($senderWallet, $request) {
            $senderWallet->balance -= $request->amount;
            $senderWallet->save();

            Transaction::create([
                'type' => 'withdraw',
               'amount' => $request->amount,
            'sender_wallet_id' => $senderWallet->id,
            'receiver_wallet_id' => null,
            ]);
        });

        return response()->json(['message' => 'Withdraw successful']);
    }

    public function transactions(Request $request)
    {
        $user = auth()->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $transactions = Transaction::where('sender_wallet_id', $wallet->id)
            ->orWhere('receiver_wallet_id', $wallet->id)
            ->with(['senderWallet.walletable',
    'receiverWallet.walletable'])

            ->paginate(10);

        return response()->json(['transactionData' => $transactions]);
    }
}
