<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Income;
use DB;
use App\Models\User;
use App\Models\Expert;
use App\Models\Transaction;
use App\Models\ExpertIncome;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorWalletController extends Controller
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
        'recipientType' => 'required|in:expert,admin',
        'amount' => 'required|numeric|min:0.01', // minimum 0.01 to prevent zero/negative amounts
    ]);

    // Additional validation based on recipient type
    if ($request->recipientType === 'expert') {
        $request->validate([
            'recipientId' => 'required|exists:experts,id',
        ]);
    }

    $sender = auth()->user();
    $senderWallet = $sender->wallet;

    if (!$senderWallet) {
        return response()->json(['message' => 'You don\'t have a wallet'], 422);
    }

    if ($senderWallet->balance < $request->amount) {
        return response()->json(['message' => 'Insufficient balance'], 422);
    }

    // Handle different recipient types
    if ($request->recipientType === 'expert') {
        $receiver = Expert::findOrFail($request->recipientId);
        $receiverWallet = $receiver->wallet;

        if (!$receiverWallet) {
            return response()->json(['message' => 'Expert does not have a wallet'], 422);
        }
    } else { // admin
        $admin = User::where('id', 4)->first();
        if (!$admin) {
            return response()->json(['message' => 'Admin account not found'], 422);
        }

        $receiverWallet = $admin->wallet;
        if (!$receiverWallet) {
            return response()->json(['message' => 'Admin does not have a wallet'], 422);
        }
    }

    DB::transaction(function () use ($senderWallet, $receiverWallet, $request) {
        // Update balances
        $senderWallet->decrement('balance', $request->amount);
        $receiverWallet->increment('balance', $request->amount);

        // Record transaction
        $transaction = Transaction::create([
            'type' => 'money_transfer',
            'amount' => $request->amount,
            'sender_wallet_id' => $senderWallet->id,
            'receiver_wallet_id' => $receiverWallet->id,
            'recipient_type' => $request->recipientType,
            'status' => 'completed',
        ]);

        // If recipient is expert, record in expert incomes
        if ($request->recipientType === 'expert') {
            ExpertIncome::create([
                'expert_id' => $receiverWallet->walletable_id,
                'income_amount' => $request->amount,
                'status' => 'complete',
                // 'transaction_id' => $transaction->id,
                // 'source' => 'money_transfer',
            ]);
        }
        else{
            Income::create([
                'order_id' => null, // Assuming no order is associated
                'income_amount' => $request->amount,
                'status' => 'complete',
               // 'transaction_id' => $transaction->id,
                //'source' => 'money_transfer',
            ]);
        }

        // You might want to create notifications for both parties here
    });

    return response()->json([
        'message' => 'Money sent successfully',
        'new_balance' => $senderWallet->fresh()->balance // return updated balance
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
