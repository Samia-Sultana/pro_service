<?php

namespace App\Interfaces\Admin;

interface AdminServiceInterface
{

public function index(array $search = []);
public function store(array $data);
public function adminDetail(int $id);
public function edit(array $data);
public function destroy(int $id);

}
