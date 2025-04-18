<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $fillable = ['balance'];

    public function walletable()
{
    return $this->morphTo();
}

public function sentTransactions()
{
    return $this->hasMany(Transaction::class, 'sender_wallet_id');
}

public function receivedTransactions()
{
    return $this->hasMany(Transaction::class, 'receiver_wallet_id');
}

}
