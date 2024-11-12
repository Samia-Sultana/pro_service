<?php

namespace App\Interfaces\Admin;

interface SubcategoryServiceInterface
{

public function index(array $search = []);
public function store(array $data);
public function categoryDetail(int $id);
public function edit(array $data);
public function destroy(int $id);

}
