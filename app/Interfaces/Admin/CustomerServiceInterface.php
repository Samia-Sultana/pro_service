<?php

namespace App\Interfaces\Admin;

interface CustomerServiceInterface
{

public function index(array $search = []);
public function store(array $data);
public function customerDetail(int $id);
public function edit(array $data);
public function destroy(int $id);

}
