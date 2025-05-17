<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Interfaces\Admin\ExpenseTypeServiceInterface;
use App\Models\ExpenseType;
use Illuminate\Support\Facades\DB;

class ExpenseTypeService implements ExpenseTypeServiceInterface
{
    private ExpenseType $expenseTypeModel;

    public function __construct(ExpenseType $expenseTypeModel)
    {
        $this->expenseTypeModel = $expenseTypeModel;
    }


    public function index($search = null)
    {
        return $this->expenseTypeModel
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function store(array $data)
    {
        return $this->expenseTypeModel->create([
            'name' => $data['name'],
            'status' => $data['status'] ?? true,
            'description' => $data['description'] ?? null,
        ]);
    }


    public function expenseTypeDetail($id)
    {
        return $this->expenseTypeModel->findOrFail($id);
    }


    public function edit(array $data)
    {
        $expenseType = $this->expenseTypeModel->findOrFail($data['id']);

        $expenseType->update([
            'name' => $data['name'],
            'status' => $data['status'] ?? true,
            'description' => $data['description'] ?? null,
        ]);

        return $expenseType;
    }


    public function destroy($id)
    {
        $expenseType = $this->expenseTypeModel->findOrFail($id);
        return $expenseType->delete();
    }
}
