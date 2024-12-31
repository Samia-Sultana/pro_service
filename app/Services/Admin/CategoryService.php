<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
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

        $query->with('parent');

        return $query->paginate(10);
    }
    public function store(array $data)
    {
        $query  = $this->categoryModel->query();

        $data['image'] = ImageHelper::processImage($data['image'] ?? null, 'category_photos');

        $category = $query->create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'parent_id' => $data['parent_id'],
            'description' => $data['description'],
            'card' => $data['card'],
            'image' => $data['image'] ?? null,
    ]);
        return $category;

    }

    public function categoryDetail($id)
    {
        $category  = $this->categoryModel->where('id', '=', $id)->first();
        return $category;

    }

    public function edit(array $data)
    {
        $query  = $this->categoryModel->query();
        $data['image'] = ImageHelper::processImage($data['image'] ?? null, 'category_photos');
        $category = $query->find($data['id']);
        $category->name = $data['name'];
        $category->slug = $data['slug'];
        $category->description = $data['description'];
        $category->card = $data['card'];
        $category->image = $data['image'];
        $category->save();

        return $category;
    }


    public function destroy($id)
    {
        $query  = $this->categoryModel->query();
        $category = $query->find($id);
        if ($category) {
            return $category->delete();
        }
        return false;
    }




}
