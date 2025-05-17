<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'department_id',
        'project_id',
        'account_id',
        'expense_type_id',
        'title',
        'amount',
        'instant_pay',
        'date',
        'attachment',
        'remarks',
    ];

    public function expenseType()
{
    return $this->belongsTo(ExpenseType::class);
}

}
