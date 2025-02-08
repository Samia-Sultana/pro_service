<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Expert extends Authenticatable implements JWTSubject
{
    protected $table = 'experts';

    use HasFactory;
    protected $fillable = [
            'name',
            'vendor_id',
            'email',
            'phone',
            'nid_number',
            'address',
            'expert_photo',
            'nid_photo',
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_expert');
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'subcategory_expert');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'expert_orders', 'expert_id', 'order_id');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
