<?php

namespace App\Interfaces\Admin;

interface ExpertServiceInterface
{

public function index();
public function store(array $data);
public function expertDetail(int $id);
public function edit(array $data);
public function destroy(int $id);

}
