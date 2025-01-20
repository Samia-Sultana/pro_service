<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
    'customer_id',
    'category_package_id',
    'area',
    'house_no',
    'road_no',
    'block',
    'district',
    'additional_info',
    'order_amount',
    'discount',
    'cupon',
    'description',
    'date',
    'slot',
    'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderPackages()
    {
        return $this->hasMany(OrderPackage::class);
    }
}
