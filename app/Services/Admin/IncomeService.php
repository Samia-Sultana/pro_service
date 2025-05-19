<?php

namespace App\Services\Admin;

use App\Models\Income;
use App\Interfaces\Admin\IncomeServiceInterface;

class IncomeService implements IncomeServiceInterface
{
    private Income $incomeModel;

    public function __construct(Income $incomeModel)
    {
        $this->incomeModel = $incomeModel;
    }

    public function index($search = null)
    {
        $query = $this->incomeModel->with('order');

        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate(10);
    }

    public function store(array $data)
    {
        return $this->incomeModel->create([
            'order_id' => $data['order_id'],
            'income_amount' => $data['income_amount'],
        ]);
    }

    public function edit(array $data)
{
    $income = $this->incomeModel->find($data['id']);

    if ($income) {
        $income->update([
            'order_id' => $data['order_id'],
            'income_amount' => $data['income_amount'],
        ]);
        return $income;
    }

    return false;
}


    public function incomeDetail($id)
    {
        return $this->incomeModel->with('order')->find($id);
    }

    public function destroy($id)
    {
        $income = $this->incomeModel->find($id);
        if ($income) {
            return $income->delete();
        }
        return false;
    }
}
