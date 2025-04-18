<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'type',
        'amount',
        'sender_wallet_id',
        'receiver_wallet_id',
        'description',
        'status',

    ];

    public function senderWallet()
{
    return $this->belongsTo(Wallet::class, 'sender_wallet_id');
}

public function receiverWallet()
{
    return $this->belongsTo(Wallet::class, 'receiver_wallet_id');
}

}
