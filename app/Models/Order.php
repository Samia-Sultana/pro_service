<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\OrderPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
    'customer_id',
    'order_type',
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

    public function experts(){
        return $this->belongsToMany(Expert::class, 'expert_orders', 'order_id', 'expert_id');
    }





}
