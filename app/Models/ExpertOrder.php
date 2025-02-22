<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ExpertOrder extends Model
{
    use Notifiable;

    protected $table = 'expert_orders';

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
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
