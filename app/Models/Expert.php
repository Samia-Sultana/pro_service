<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
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
}
