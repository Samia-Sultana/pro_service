<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Interfaces\Admin\OrderPackageInterface;
use App\Models\CategoryPackage;
use App\Models\OrderPackage;

class orderPackageService implements OrderPackageInterface
{
    private  $orderPackageModel;
    private $categoryPackageModel;
    public function __construct(OrderPackage $orderPackageModel, CategoryPackage $categoryPackageModel)
    {
        $this->orderPackageModel = $orderPackageModel;
        $this->categoryPackageModel = $categoryPackageModel;
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
        $categoryPackage = $this->categoryPackageModel->find($categoryPackageId);
        $orderPackage = $this->orderPackageModel->create([
            'order_id' => $order,
            'category_package_id' => $categoryPackageId,
            'category_id' => $categoryPackage->category_id,
            'price' => $categoryPackage->price,
            'discount' => $categoryPackage->discount,
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

    public function update(array $data)
    {
        $orderId = $data['services'][0]['order_id'];
        $deletedOrderPackages = OrderPackage::where('order_id', $orderId)->delete();
        info($data['services']);


        try{

            foreach($data['services'] as $package){
                $this->orderPackageModel->create([
                    'order_id' => $package['order_id'],
                    'category_package_id' => $package['category_package_id'],
                    'category_id' => $package['category_id'],
                    'price' => $package['price'],
                    'discount' => $package['discount'],

                    ]);
            }

        }catch(\Exception $e){

        }

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
