<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\CategoryServiceInterface;
use App\Models\Category;

class CategoryService implements CategoryServiceInterface
{
    private Category $categoryModel;
    public function __construct(Category $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }
    public function index($search = null)
    {
        $query  = $this->categoryModel->query();

        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate(10);
    }


}
