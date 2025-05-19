<?php

namespace App\Interfaces\Admin;

interface IncomeServiceInterface
{

public function index();
public function store(array $data);
public function incomeDetail(int $id);
public function edit(array $data);
public function destroy(int $id);

}
