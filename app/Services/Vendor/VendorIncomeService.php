<?php

namespace App\Services\Vendor;

use App\Models\Income;
use App\Interfaces\Vendor\VendorIncomeServiceInterface;
use App\Models\VendorIncome;

class VendorIncomeService implements VendorIncomeServiceInterface
{
    private VendorIncome $vendorIncomeModel;

    public function __construct(VendorIncome $vendorIncomeModel)
    {
        $this->vendorIncomeModel = $vendorIncomeModel;
    }

    public function index($search = null, $id)
    {
        $query = $this->vendorIncomeModel->where('vendor_id', $id);

        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate(10);
    }


}
