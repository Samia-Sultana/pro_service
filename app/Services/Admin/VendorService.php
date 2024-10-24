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
    public function index()
    {
        $query  = $this->vendorModel->query();
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
        $admin = $query->findOrFail($id);
        return $admin;

    }

    public function edit(array $data)
    {
        $query  = $this->vendorModel->query();
        $vendor = $query->find($data['id']);
        $vendor->name = $data['name'];
        $vendor->save();
        $vendor->permissions()->sync($data['permissions']);
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
