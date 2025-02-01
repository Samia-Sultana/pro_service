<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Interfaces\Admin\OrderPackageInterface;
use App\Interfaces\Expert\OrderServiceInterface;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    protected $orderService;


    public function __construct(OrderServiceInterface $orderService){
        $this->orderService = $orderService;

    }

    public function allOrder(){


    }

    public function currentOrder(){

    }

    public function orderDetail(){

    }

    public function updateOrderStatus(){

    }






}
