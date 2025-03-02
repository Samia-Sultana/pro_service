<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable implements JWTSubject
{
    protected $table = 'vendors';
    use HasFactory;

    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'nid_number',
        'service_status',
        'login_status',
        'nid_photo',
        'vendor_photo',
        'password',
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
