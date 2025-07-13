<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\ExpertIncome;
use App\Models\Transaction;
use DB;
use Illuminate\Http\Request;

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
            'selectedExpert' => 'required|exists:experts,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $sender = auth()->user();
        $senderWallet = $sender->wallet;
    if (!$senderWallet || $senderWallet->balance < $request->amount) {
        return response()->json(['message' => 'Insufficient balance'], 422);
    }

    $receiver = Expert::findOrFail($request->selectedExpert);
    $receiverWallet = $receiver->wallet;
    info($receiver);
    if (!$receiverWallet) {
        return response()->json(['message' => 'Expert does not have a wallet'], 422);
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

        ExpertIncome::create([
            'expert_id' => $receiverWallet->walletable_id,
            'income_amount' => $request->amount,
            'status' => 'complete',
            // 'order_id' => 'n/a',
            // 'category_id' => 'n/a',
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
