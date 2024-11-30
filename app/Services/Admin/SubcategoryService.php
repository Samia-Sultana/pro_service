<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Interfaces\Admin\SubcategoryServiceInterface;
use App\Models\subcategory;

class SubcategoryService implements SubcategoryServiceInterface
{
    private Subcategory $subcategoryModel;
    public function __construct(Subcategory $subcategoryModel)
    {
        $this->subcategoryModel = $subcategoryModel;
    }
    public function index($search = null)
    {
        $query = $this->subcategoryModel->with('category');

        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate(10);
    }

    public function store(array $data)
    {
        $query  = $this->subcategoryModel->query();

        $data['image'] = ImageHelper::processImage($data['image'] ?? null, 'subcategory_photos');

        $subcategory = $query->create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'card' => $data['card'],
            'image' => $data['image'] ?? null,
            'category_id' => $data['category_id']
    ]);
        return $subcategory;

    }

    public function categoryDetail($id)
    {
        $subcategory  = $this->subcategoryModel->where('id', '=', $id)->first();
        return $subcategory;

    }

    public function edit(array $data)
    {
        $query  = $this->subcategoryModel->query();
        $data['image'] = ImageHelper::processImage($data['image'] ?? null, 'subcategory_photos');
        $subcategory = $query->find($data['id']);
        $subcategory->name = $data['name'];
        $subcategory->slug = $data['slug'];
        $subcategory->description = $data['description'];
        $subcategory->card = $data['card'];
        $subcategory->image = $data['image'];
        $subcategory->category_id = $data['category_id'];
        $subcategory->save();

        return $subcategory;
    }


    public function destroy($id)
    {
        $query  = $this->subcategoryModel->query();
        $subcategory = $query->find($id);
        if ($subcategory) {
            return $subcategory->delete();
        }
        return false;
    }


}
