<?php

namespace App\Interfaces\Admin;

interface RoleServiceInterface
{

public function index();
public function store(array $data);
public function getRolePermissions(int $roleId);
public function edit(array $data);
public function destroy(int $id);

}
