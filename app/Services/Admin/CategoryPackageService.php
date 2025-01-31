<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\CategoryPackageServiceInterface;
use App\Models\CategoryPackage;

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
            $query->whereIn('category_id', $categories);        }

        return $query->paginate(10);
    }
    public function store(array $data)
    {
        $query  = $this->categoryPackageModel->query();
        $package = $query->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
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
