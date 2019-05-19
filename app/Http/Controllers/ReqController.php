<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use DB;
use UserFunction;

class ReqController extends Controller
{
    public function __construct()
    {
      UserFunction::checkAuth();
    }



    public function requestorder(Request $request)
    {
      $requestorder = DB::connection('oracle')->select("SELECT * FROM inventories");
      //print_r($inventory);
      return view("requestorder")->with("request", $requestorder);
    }

    

    /*public function useradd()
    {
      return view("useradd");
    }*/
}
