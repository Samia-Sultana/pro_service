<?php

namespace App\Interfaces\Admin;

interface ExpenseServiceInterface
{

public function index();
public function store(array $data);
public function expenseDetail(int $id);
public function edit(array $data);
public function destroy(int $id);

}
