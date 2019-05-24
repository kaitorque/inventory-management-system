<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use DB;
use UserFunction;

class DelController extends Controller
{
    public function __construct()
    {
      $this->middleware('checkauth');
    }

    public function deliveredlist(Request $request)
    {
      $delivered = DB::connection('oracle')->select("SELECT d.delivered_id, TO_CHAR(d.created_date,'DD-MM-YYYY HH:MI AM') as fmmodified_date, NVL(u.nickname, u.emp_id) as ncreated_by,
        d.status, SUM(b.qty * i.original_cost) totalcost
        FROM delivered d
        INNER JOIN delivered_item b on d.delivered_id = b.delivered_id
        INNER JOIN inventories i on i.product_id = b.product_id
        INNER JOIN users u on u.emp_id = d.modified_by
        GROUP BY d.delivered_id, TO_CHAR(d.created_date,'DD-MM-YYYY HH:MI AM'), NVL(u.nickname, u.emp_id), d.status, d.created_date
        ORDER BY d.created_date desc");
      $delivered = array_map(function($row){
        $row->link = UserFunction::encrypt("did={$row->delivered_id}");
        return $row;
      },$delivered);
      if ($request->isMethod('get'))
      {
        return view("deliveredlist");
      }
      else if($request->isMethod('post'))
      {
        return response()->json([
          "success" => true,
          "response" => "Search List Successful",
          "data" => $delivered,
          "link" => route("deliverededit"),
        ]);
      }
    }

    public function deliveredadd()
    {
      return view("deliveredadd");
    }

    public function deliveredadd_post(Request $request)
    {
      //Declare custom error message for custom validator
      $errorMsg = [];
      //Using laravel validator for input
      $rules = [
      'did' => 'required|numeric',
      'status' => 'required',
      ];
      $message = [
        'did.required' => 'Delivered ID is required.',
        'did.numeric' => 'Delivered ID must be numeric',
        'status.required' => 'Status is required.',
      ];
      $validator = Validator::make(Input::all(), $rules, $message);
      if($validator->fails()) {
        return response()->json([
          "success" => false,
          "response" => $validator->errors()->all(),
        ]);
      }
      //Custom Validation
      $select = DB::connection("oracle")->select("SELECT * FROM delivered WHERE delivered_id = ? ", [$request->did]);
      if(!empty($select))
      {
        $errorMsg[]="Delivered ID entered already exist. Please choose different Delivered ID!";
      }
      if(empty($request->pid)||empty($request->qty))
      {
        $errorMsg[]="Product cannot empty!";
      }
      $reqitem = [];
      for($i = 0; $i < sizeof($request->qty); $i++)
      {
        if($request->qty[$i] > 0)
          array_push($reqitem, ["delivered_id" => $request->did, "product_id" => $request->pid[$i], "qty" => $request->qty[$i] ]);
      }
      if(empty($reqitem))
      {
        $errorMsg[]="Product cannot empty!";
      }
      //If no error
      if(empty($errorMsg))
      {
        $data = [
          "delivered_id" => $request->did,
          "status" => $request->status,
          "created_date" => DB::raw("sysdate"),
          "created_by" => session("empid"),
          "modified_date" => DB::raw("sysdate"),
          "modified_by" => session("empid"),
        ];
        DB::connection("oracle")->table("delivered")->insert($data);
        foreach($reqitem as $item)
        {
          DB::connection("oracle")->table("delivered_item")->insert($item);
          $update = [
            "quantity" => DB::raw("quantity+{$item['qty']}")
          ];
          DB::connection("oracle")->table("inventories")->where("product_id", $item['product_id'])->update($update);
        }
        return response()->json([
          "success" => true,
          "response" => "Delivered successfully added!"
        ]);
      }

      //Return for custom validation
      return response()->json([
        "success" => false,
        "response" => $errorMsg,
      ]);
    }

    public function deliverededit(Request $request)
    {
      $req = UserFunction::odecrypt($request->q);
      $delivered = DB::connection("oracle")->select("SELECT d.delivered_id, d.status,
      i.product_id, i.category, i.brand, i.model, b.qty, i.original_cost, b.qty*i.original_cost total,
      TO_CHAR(d.created_date,'DD-MM-YYYY HH:MI AM') as fmcreated_date,
      TO_CHAR(d.modified_date,'DD-MM-YYYY HH:MI AM') as fmmodified_date,
      u2.nickname createdby, u3.nickname modifiedby
      FROM delivered d
      INNER JOIN delivered_item b ON b.delivered_id = d.delivered_id
      INNER JOIN inventories i ON i.product_id = b.product_id
      INNER JOIN users u2 on u2.emp_id = d.created_by
      INNER JOIN users u3 on u3.emp_id = d.modified_by
      WHERE d.delivered_id = ? ", [$req->did]);
      return view("deliverededit")->with("delivered", $delivered);
    }

    public function deliverededit_post(Request $request)
    {
      $did = UserFunction::decrypt($request->encdid);
      //Declare custom error message for custom validator
      $errorMsg = [];
      //Using laravel validator for input
      $rules = [
      'status' => 'required'
      ];
      $message = [
        'status.required' => 'Status is required.',
      ];
      $validator = Validator::make(Input::all(), $rules, $message);
      if($validator->fails()) {
        return response()->json([
          "success" => false,
          "response" => $validator->errors()->all(),
        ]);
      }
      //Custom Validation
      //If no error
      if(empty($errorMsg))
      {
        $data = [
          "status" => $request->status,
          "modified_date" => DB::raw("sysdate"),
          "modified_by" => session("empid"),
        ];
        $affected = DB::connection("oracle")->table("delivered")->where("delivered_id", $did)->update($data);
        if($affected == 0)
        {
          $response = "No change detected. No record updated!" ;
        }
        else
        {
          $response = "Delivered successfully updated!";
        }
        return response()->json([
          "success" => true,
          "response" => $response,
        ]);
      }

      //Return for custom validation
      return response()->json([
        "success" => false,
        "response" => $errorMsg,
      ]);
    }

    public function delivereddel(Request $request)
    {
      $delid = UserFunction::odecrypt($request->delid);
      $affected = DB::connection("oracle")->table("delivered_item")->where("delivered_id", $delid->did)->delete();
      $affected = DB::connection("oracle")->table("delivered")->where("delivered_id", $delid->did)->delete();
      if($affected > 0)
      {
        return response()->json([
          "success" => true,
          "response" => "Delivered record successfully deleted!",
        ]);
      }

      return response()->json([
        "success" => false,
        "response" => ["Record not found. No delete!"],
      ]);
    }
}
