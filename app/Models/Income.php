<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['order_id', 'income_amount', 'remarks', 'attachment', 'date', 'status'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
