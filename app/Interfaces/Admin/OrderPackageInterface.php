<?php

namespace App\Interfaces\Admin;

interface OrderPackageInterface
{

public function index();
public function store(array $data, $order);
public function orderPackageDetail(int $id);
public function update(array $data);
public function destroy(int $id);



}
