<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    use HasFactory;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wallet()
{
    return $this->morphOne(Wallet::class, 'walletable');
}

}
