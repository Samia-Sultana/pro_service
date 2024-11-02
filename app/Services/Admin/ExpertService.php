<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Interfaces\Admin\ExpertServiceInterface;
use App\Models\Expert;

class ExpertService implements ExpertServiceInterface
{
    private Expert $expertModel;
    public function __construct(Expert $expertModel)
    {
        $this->expertModel = $expertModel;
    }
    public function index($search = null)
    {
        $query  = $this->expertModel->query();
        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        return $query->paginate(10);
    }
    public function store(array $data)
    {
        $query  = $this->expertModel->query();

        $data['expert_photo'] = ImageHelper::processImage($data['expert_photo'] ?? null, 'expert_photos');
        $data['nid_photo'] = ImageHelper::processImage($data['nid_photo'] ?? null, 'nid_photos');

        $expert = $query->create([
            'name' => $data['name'],
            'vendor_id' => $data['vendor_id'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'nid_number' => $data['nid_number'],
            'address' => $data['address'],
            'expert_photo' => $data['expert_photo'] ?? null,
            'nid_photo' => $data['nid_photo'] ?? null,
    ]);
        return $expert;

    }

    public function expertDetail($id)
    {
        $expert  = $this->expertModel->where('id', '=', $id)->with('vendor','categories','subcategories')->get();
        return $expert;

    }

    public function edit(array $data)
    {
        $query  = $this->expertModel->query();
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
        $query  = $this->expertModel->query();
        $expert = $query->find($id);
        if ($expert) {
            return $expert->delete();
        }
        return false;
    }


}
