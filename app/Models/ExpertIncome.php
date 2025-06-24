<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertIncome extends Model
{
    protected $fillable = [
        'order_id',
        'income_amount',
        'remarks',
        'attachment',
        'date',
        'status',
        'expert_id',
        'category_id'
    ];


}
