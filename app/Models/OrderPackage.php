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

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function categoryPackage()
    {
        return $this->belongsTo(CategoryPackage::class, 'category_package_id');
    }
}
