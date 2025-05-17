<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [

        'expense_type_id',
        'title',
        'amount',
        'instant_pay',
        'date',
        'remarks',

        'attachment',
    ];

    public function expenseType()
{
    return $this->belongsTo(ExpenseType::class);
}

}
