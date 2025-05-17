<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Interfaces\Admin\ExpenseServiceInterface;
use App\Models\Expense;

class ExpenseService implements ExpenseServiceInterface
{
    private Expense $expenseModel;

    public function __construct(Expense $expenseModel)
    {
        $this->expenseModel = $expenseModel;
    }

    public function index($search = null)
    {
        $query = $this->expenseModel->query()->with('expenseType');

        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate(10);
    }

    public function store(array $data)
    {
        $query = $this->expenseModel->query();

        $data['image'] = ImageHelper::processImage($data['image'] ?? null, 'expense_images');

        $expense = $query->create([
            'title' => $data['title'],
            'amount' => $data['amount'],
            'expense_type_id' => $data['expense_type_id'],
            'description' => $data['description'] ?? null,
            'date' => $data['date'],
            'image' => $data['image'] ?? null,
        ]);

        return $expense;
    }

    public function expenseDetail($id)
    {
        return $this->expenseModel->where('id', $id)
            ->with('expenseType')
            ->first();
    }

    public function edit(array $data)
    {
        $query = $this->expenseModel->query();

        $data['image'] = ImageHelper::processImage($data['image'] ?? null, 'expense_images');

        $expense = $query->find($data['id']);
        $expense->title = $data['title'];
        $expense->amount = $data['amount'];
        $expense->expense_type_id = $data['expense_type_id'];
        $expense->description = $data['description'] ?? null;
        $expense->date = $data['date'];
        if (!empty($data['image'])) {
            $expense->image = $data['image'];
        }

        $expense->save();

        return $expense;
    }

    public function destroy($id)
    {
        $query = $this->expenseModel->query();
        $expense = $query->find($id);
        if ($expense) {
            return $expense->delete();
        }
        return false;
    }
}
