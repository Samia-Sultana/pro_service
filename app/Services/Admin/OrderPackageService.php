<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Interfaces\Admin\OrderPackageInterface;
use App\Models\OrderPackage;

class orderPackageService implements OrderPackageInterface
{
    private  $orderPackageModel;
    public function __construct(OrderPackage $orderPackageModel)
    {
        $this->orderPackageModel = $orderPackageModel;
    }
    public function index($search = null)
    {
        $query  = $this->orderPackageModel->query();
        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        return $query->paginate(10);
    }
    public function store(array $data, $order)
{
    $orderPackages = [];

    foreach ($data['category_package_ids'] as $categoryPackageId) {
        $orderPackage = $this->orderPackageModel->create([
            'order_id' => $order,
            'category_package_id' => $categoryPackageId,

        ]);

        $orderPackages[] = $orderPackage;
    }

    return $orderPackages;
}


    public function orderPackageDetail($id)
    {
        $order  = $this->orderPackageModel->where('id', '=', $id)->get();
        return $order;

    }

    public function edit(array $data)
    {
        $query  = $this->orderPackageModel->query();
        $data['expert_photo'] = ImageHelper::processImage($data['expert_photo'] ?? null, 'expert_photos');
        $data['nid_photo'] = ImageHelper::processImage($data['nid_photo'] ?? null, 'nid_photos');

        $expert = $query->find($data['id']);
        $expert->name = $data['name'];
        $expert->vendor_id = $data['vendor_id'];
        $expert->phone = $data['phone'];
        $expert->email = $data['email'];
        $expert->nid_number = $data['nid_number'];
        $expert->nid_photo = $data['nid_photo'];
        $expert->expert_photo = $data['expert_photo'];
        $expert->address = $data['address'];
        $expert->save();

        return $expert;
    }


    public function destroy($id)
    {
        $query  = $this->orderPackageModel->query();
        $order = $query->find($id);
        if ($order) {
            return $order->delete();
        }
        return false;
    }


}
