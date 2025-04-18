<?php

namespace App\Interfaces\Admin;

interface CustomerWalletServiceInterface
{

public function index();
public function addMoney();
public function withdraw();
public function sendMoney();


}
