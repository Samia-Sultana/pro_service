<?php

namespace App\Services\Vendor;

use App\Helpers\ImageHelper;
use App\Interfaces\Vendor\ExpertServiceInterface;
use App\Models\Expert;
use App\Models\Order;

class ExpertService implements ExpertServiceInterface
{
    private Expert $expertModel;
    public function __construct(Expert $expertModel)
    {
        $this->expertModel = $expertModel;
    }

    public function allExpert(int $id){
        $experts  = $this->expertModel->where('vendor_id', "=", $id)->get();
        return $experts;
     }








}
