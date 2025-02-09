<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Interfaces\Vendor\ExpertServiceInterface;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;

class ExpertController extends Controller
{
    protected $expertService;


    public function __construct(ExpertServiceInterface $expertService){
        $this->expertService = $expertService;

    }



    public function allExpert($id){
        $experts = $this->expertService->allExpert($id);
        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $experts
        ]);
    }



}
