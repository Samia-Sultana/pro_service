<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\CustomerWalletServiceInterface;
use App\Models\Wallet;

class CustomerWalletService implements CustomerWalletServiceInterface
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
