<?php

namespace App\Services\Expert;

use App\Models\Income;
use App\Interfaces\Expert\ExpertIncomeServiceInterface;
use App\Models\ExpertIncome;

class ExpertIncomeService implements ExpertIncomeServiceInterface
{
    private ExpertIncome $expertIncomeModel;

    public function __construct(ExpertIncome $expertIncomeModel)
    {
        $this->expertIncomeModel = $expertIncomeModel;
    }

    public function index($search = null, $id)
    {
        $query = $this->expertIncomeModel->where('expert_id', $id);

        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate(10);
    }


}
