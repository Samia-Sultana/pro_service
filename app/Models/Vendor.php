<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
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
}
