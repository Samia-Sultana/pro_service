<?php

namespace App\Interfaces\Admin;

interface OrderServiceInterface
{

public function index();
public function store(array $data);

public function orderDetail(int $id);
public function destroy(int $id);
public function updateOrderStatus($order);

}
