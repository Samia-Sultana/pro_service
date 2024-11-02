<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\SubcategoryServiceInterface;
use App\Models\Category;
use App\Models\Subcategory;

class SubcategoryService implements SubcategoryServiceInterface
{
    private Subcategory $subcategoryModel;
    public function __construct(Subcategory $subcategoryModel)
    {
        $this->subcategoryModel = $subcategoryModel;
    }
    public function index($search = null)
    {
        $query  = $this->subcategoryModel->query();

        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate(10);
    }


}
