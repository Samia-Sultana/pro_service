<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPackage extends Model
{
    protected $fillable = [
        'order_id',
        'category_package_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
