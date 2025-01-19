<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Interfaces\Admin\OrderServiceInterface;
use App\Models\Order;

class OrderService implements OrderServiceInterface
{
    private Order $orderModel;
    public function __construct(Order $orderModel)
    {
        $this->orderModel = $orderModel;
    }
    public function index($search = null)
    {
        $query  = $this->orderModel->query();
        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        return $query->paginate(10);
    }
    public function store(array $data)
    {
        $query  = $this->orderModel->query();
        $order = $query->create([
        'category_id' => $data['category_id'],
        'customer_id' => $data['customer_id'],
        'category_package_id' => $data['category_package_id'],
        'area' => $data['area'],
        'house_no' => $data['house_no'],
        'road_no' => $data['road_no'],
        'block' => $data['block'],
        'district' => $data['district'],
        'additional_info' => $data['additional_info'] ?? null,
        'order_amount' => $data['order_amount'],
        'discount' => $data['discount'] ?? 0,
        'cupon' => $data['cupon'] ?? null,
        'description' => $data['description'] ?? null,
        'date' => $data['date'],
        'slot' => $data['slot'],
        'status' => $data['status'] ?? 'pending',
        ]);
        return $order;

    }

    public function orderDetail($id)
    {
        $order  = $this->orderModel->where('id', '=', $id)->first();
        return $order;

    }

    public function edit(array $data)
    {
        $query  = $this->orderModel->query();
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
        $query  = $this->orderModel->query();
        $order = $query->find($id);
        if ($order) {
            return $order->delete();
        }
        return false;
    }


}
