<?php

namespace App\Interfaces\Admin;

interface CategoryPackageServiceInterface
{

public function index(array $categories = []);
public function store(array $data);
public function packageDetail(int $id);
public function edit(array $data);
public function destroy(int $id);

}
