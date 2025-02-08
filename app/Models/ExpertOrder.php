<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertOrder extends Model
{
    protected $fillable = [
        'expert_id',
        'order_id',
        'category_id',
        'time',
        'date',
        'status',
        'payment_status',
        'vendor_id',

    ];
}
