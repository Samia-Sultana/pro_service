<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorIncome extends Model
{
    protected $fillable = ['order_id', 'income_amount', 'remarks', 'attachment', 'date', 'status', 'vendor_id', 'category_id'];



}
