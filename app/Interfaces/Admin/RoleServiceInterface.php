<?php

namespace App\Interfaces\Admin;

interface RoleServiceInterface
{

public function index();
public function store(array $data);
public function roleDetail(int $id);
public function edit(array $data);
public function destroy(int $id);

}
