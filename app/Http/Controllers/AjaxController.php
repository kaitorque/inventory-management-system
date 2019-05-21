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

    public function checkpid(Request $request)
    {
      $check = DB::connection("oracle")->select("SELECT * FROM inventories WHERE product_id = ? ", [$request->pid]);
      if(empty($check))
        return "true";
      else
        return "false";
    }

    public function checkrid(Request $request)
    {
      $check = DB::connection("oracle")->select("SELECT * FROM requests WHERE request_id = ? ", [$request->rid]);
      if(empty($check))
        return "true";
      else
        return "false";
    }

    public function checkdid(Request $request)
    {
      $check = DB::connection("oracle")->select("SELECT * FROM delivered WHERE delivered_id = ? ", [$request->did]);
      if(empty($check))
        return "true";
      else
        return "false";
    }

    public function checkpuid(Request $request)
    {
      $check = DB::connection("oracle")->select("SELECT * FROM purchases WHERE purchases_id = ? ", [$request->puid]);
      if(empty($check))
        return "true";
      else
        return "false";
    }

    public function invlist(Request $request)
    {
        // $limit = 50;
        $bind = [];
        $condition = [];
        $where = "";
        if(!empty($request->mpid))
        {
          $condition[] = " product_id LIKE ? ";
          $bind[] = "%$request->mpid%";
        }
        if(!empty($request->mcategory))
        {
          $condition[] = " category LIKE ? ";
          $bind[] = "%$request->mcategory%";
        }
        if(!empty($request->mbrand))
        {
          $condition[] = " brand LIKE ? ";
          $bind[] = "%$request->mbrand%";
        }
        if(!empty($request->mmodel))
        {
          $condition[] = " model LIKE ? ";
          $bind[] = "%$request->mmodel%";
        }
        if(!empty($condition))
        {
          $where = " WHERE ";
        }
        $strcond = implode(" AND ", $condition);
        // $bind[] = $limit;
        $list = DB::connection("oracle")->select("SELECT product_id, category, brand, model, original_cost, retail_price
          from inventories
          {$where} {$strcond}", $bind);

        return response()->json([
          "success" => true,
          "response" => "Found Inventory",
          "data" => $list,
        ]);
    }
}
