<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Interfaces\Admin\VendorServiceInterface;
use App\Models\vendor;

class VendorService implements VendorServiceInterface
{
    private Vendor $vendorModel;
    public function __construct(Vendor $vendorModel)
    {
        $this->vendorModel = $vendorModel;
    }
    public function index($search = null)
    {
        $query  = $this->vendorModel->query();
        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        return $query->paginate(10);
    }
    public function store(array $data)
    {
        $query  = $this->vendorModel->query();

        $data['vendor_photo'] = ImageHelper::processImage($data['vendor_photo'] ?? null, 'vendor_photos');
        $data['nid_photo'] = ImageHelper::processImage($data['nid_photo'] ?? null, 'nid_photos');

        $vendor = $query->create([
            'name' => $data['name'],
            'company_name' => $data['company_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'nid_number' => $data['nid_number'],
            'service_status' => $data['service_status'],
            'login_status' => $data['login_status'],
            'vendor_photo' => $data['vendor_photo'] ?? null,
            'nid_photo' => $data['nid_photo'] ?? null,
    ]);
        return $vendor;

    }

    public function vendorDetail($id)
    {
        $query  = $this->vendorModel->query();
        $vendor = $query->findOrFail($id);
        return $vendor;

    }

    public function edit(array $data)
    {
        $query  = $this->vendorModel->query();
        $data['vendor_photo'] = ImageHelper::processImage($data['vendor_photo'] ?? null, 'vendor_photos');
        $data['nid_photo'] = ImageHelper::processImage($data['nid_photo'] ?? null, 'nid_photos');

        $vendor = $query->find($data['id']);
        $vendor->name = $data['name'];
        $vendor->company_name = $data['company_name'];
        $vendor->phone = $data['phone'];
        $vendor->email = $data['email'];
        $vendor->nid_number = $data['nid_number'];
        $vendor->nid_photo = $data['nid_photo'];
        $vendor->vendor_photo = $data['vendor_photo'];
        $vendor->service_status = $data['service_status'];
        $vendor->login_status = $data['login_status'];
        $vendor->save();

        return $vendor;
    }

    public function destroy($id)
    {
        $query  = $this->vendorModel->query();
        $vendor = $query->find($id);
        if ($vendor) {
            return $vendor->delete();
        }
        return false;
    }


}
