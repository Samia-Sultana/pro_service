<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use DB;
use Illuminate\Http\Request;

class AdminWalletController extends Controller
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
    public function sendMoney(Request $request)
    {
        $request->validate([
            'selectedVendor' => 'required|exists:vendors,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $sender = auth()->user();
        $senderWallet = $sender->wallet;
    if (!$senderWallet || $senderWallet->balance < $request->amount) {
        return response()->json(['message' => 'Insufficient balance'], 422);
    }

    $receiver = Vendor::findOrFail($request->selectedVendor);
    $receiverWallet = $receiver->wallet;
    if (!$receiverWallet) {
        return response()->json(['message' => 'Vendor does not have a wallet'], 422);
    }


 DB::transaction(function () use ($senderWallet, $receiverWallet, $request) {
        $senderWallet->decrement('balance', $request->amount);
        $receiverWallet->increment('balance', $request->amount);

        Transaction::create([
            'type' => 'send_money',
            'amount' => $request->amount,
            'sender_wallet_id' => $senderWallet->id,
            'receiver_wallet_id' => $receiverWallet->id,

        ]);
    });


        return response()->json(['message' => 'Money sent successfully']);
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
