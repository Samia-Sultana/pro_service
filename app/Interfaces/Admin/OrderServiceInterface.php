<?php

namespace App\Interfaces\Admin;

interface OrderServiceInterface
{

public function index();
public function store(array $data);
public function orderDetail(int $id);
public function edit(array $data);
public function destroy(int $id);

}
