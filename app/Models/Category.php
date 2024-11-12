<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'card',

];
    public function experts()
    {
        return $this->belongsToMany(Expert::class, 'category_expert');
    }
}
