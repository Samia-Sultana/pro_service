<?php

namespace App\Interfaces\Admin;

interface ExpenseTypeServiceInterface
{

public function index();
public function store(array $data);
public function expenseTypeDetail(int $id);
public function edit(array $data);
public function destroy(int $id);

}
