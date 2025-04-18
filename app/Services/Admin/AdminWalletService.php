<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\AdminWalletServiceInterface;
use App\Models\Wallet;

class AdminWalletService implements AdminWalletServiceInterface
{
    private Wallet $walletModel;
    public function __construct(Wallet $walletModel)
    {
        $this->walletModel = $walletModel;
    }

    public function index(){

    }
    public function addMoney()
    {
    }

    public function withdraw()
    {
    }

    public function sendMoney()
    {
    }




}
