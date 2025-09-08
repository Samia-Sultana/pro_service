<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Models\CategoryPackage;
use App\Interfaces\Admin\CategoryPackageServiceInterface;

class CategoryPackageService implements CategoryPackageServiceInterface
{
    private CategoryPackage $categoryPackageModel;
    public function __construct(CategoryPackage $categoryPackageModel)
    {
        $this->categoryPackageModel = $categoryPackageModel;
    }

    public function index(array $categories = [])
    {
        $query  = $this->categoryPackageModel->query();
        // if (!empty($search)) {
        //     foreach ($search as $field => $value) {
        //         $query->where($field, 'like', '%' . $value . '%');
        //     }
        // }
        if(!empty($categories)){
            $query->whereIn('category_id', $categories);
        }


        return $query->paginate(10);
    }

    public function allPackage()
    {
        $query  = $this->categoryPackageModel->query();
        return $query->paginate(10);
    }
    public function store(array $data)
    {
        $query  = $this->categoryPackageModel->query();
        $package = $query->create([
            'name' => $data['name'],
            'tag' => $data['tag'],
            'category_id' => $data['category_id'] ?? null,
            'description' => $data['description'],
            'image' => $data['image'] ?? null,
            'price' => $data['price'],
            'discount' => $data['discount'] ?? null,
        ]);
        return $package;

    }

    public function packageDetail($id)
    {
        $query  = $this->categoryPackageModel->query();
        $package = $query->findOrFail($id);
        return $package;

    }

    public function edit(array $data)
    {
        $query  = $this->categoryPackageModel->query();
        $data['image'] = ImageHelper::processImage($data['image'] ?? null, 'package_photos');
        $package = $query->find($data['id']);
        $package->name = $data['name'];
        $package->tag = $data['tag'];
        $package->category_id = $data['category_id'];
        $package->description = $data['description'];
        $package->price = $data['price'];
        $package->discount = $data['discount'];
        $package->image = $data['image'];
        $package->save();

        return $package;

    }

    public function destroy($id)
    {
        $query  = $this->categoryPackageModel->query();
        $package = $query->find($id);
        if ($package) {
            return $package->delete();
        }
        return false;
    }


}
