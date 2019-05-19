<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use DB;
use UserFunction;

class InvController extends Controller
{
    public function __construct()
    {
      UserFunction::checkAuth();
    }



    public function inventorylist(Request $request)
    {
      $inventory = DB::connection('oracle')->select("SELECT * FROM inventories");
      //print_r($inventory);
      return view("inventorylist")->with("inventory", $inventory);
    }

    /*public function useradd()
    {
      return view("useradd");
    }*/
}
