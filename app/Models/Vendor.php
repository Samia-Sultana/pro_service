<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Vendor extends Authenticatable implements JWTSubject
{
    protected $table = 'vendors';
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'nid_number',
        'service_status',
        'login_status',
        'nid_photo',
        'vendor_photo'
    ];
    public function experts()
    {
        return $this->hasMany(Expert::class);
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
