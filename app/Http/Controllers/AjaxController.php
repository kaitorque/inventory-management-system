<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use DB;
use UserFunction;

class AjaxController extends Controller
{
    public function __contruct()
    {
      $this->middleware('checkauth');
    }

    public function checkempid(Request $request)
    {
      $check = DB::connection("oracle")->select("SELECT * FROM users WHERE emp_id = ? ", [$request->empid]);
      if(empty($check))
        return "true";
      else
        return "false";
    }

    public function checknname(Request $request)
    {
      $check = DB::connection("oracle")->select("SELECT * FROM users WHERE nickname = ? ", [$request->nname]);
      if(empty($check))
        return "true";
      else
        return "false";
    }

    public function checknnameedit(Request $request)
    {
      $empid = UserFunction::decrypt($request->encempid);
      $check = DB::connection("oracle")->select("SELECT * FROM users WHERE nickname = ? AND emp_id != ? ", [$request->nname, $empid]);
      if(empty($check))
        return "true";
      else
        return "false";
    }
}
