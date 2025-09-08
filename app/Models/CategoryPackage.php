<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPackage extends Model
{
    use HasFactory;
    protected $table = 'category_packages';
    protected $fillable = [
        'name',
        'tag',
        'category_id',
        'description',
        'image',
        'price',
        'discount',
    ];
}
