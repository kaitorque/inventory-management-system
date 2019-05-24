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
      $this->middleware('checkauth');
    }

    public function requestlist(Request $request)
    {
      $requests = DB::connection('oracle')->select("SELECT r.request_id, TO_CHAR(r.created_date,'DD-MM-YYYY HH:MI AM') as fmmodified_date, NVL(u.nickname, u.emp_id) as ncreated_by,
        r.status, SUM(b.qty * i.original_cost) totalcost
        FROM requests r
        INNER JOIN request_item b on r.request_id = b.request_id
        INNER JOIN inventories i on i.product_id = b.product_id
        INNER JOIN users u on u.emp_id = r.modified_by
        GROUP BY r.request_id, TO_CHAR(r.created_date,'DD-MM-YYYY HH:MI AM'), NVL(u.nickname, u.emp_id), r.status, r.created_date
        ORDER BY r.created_date DESC");
      $requests = array_map(function($row){
        $row->link = UserFunction::encrypt("rid={$row->request_id}");
        return $row;
      },$requests);
      if ($request->isMethod('get'))
      {
        return view("requestlist");
      }
      else if($request->isMethod('post'))
      {
        return response()->json([
          "success" => true,
          "response" => "Search List Successful",
          "data" => $requests,
          "link" => route("requestedit"),
        ]);
      }
    }

    public function requestadd()
    {
      return view("requestadd");
    }

    public function requestadd_post(Request $request)
    {
      //Declare custom error message for custom validator
      $errorMsg = [];
      //Using laravel validator for input
      $rules = [
      'rid' => 'required|numeric',
      'status' => 'required',
      ];
      $message = [
        'rid.required' => 'Request ID is required.',
        'rid.numeric' => 'Request ID must be numeric',
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
      $select = DB::connection("oracle")->select("SELECT * FROM requests WHERE request_id = ? ", [$request->rid]);
      if(!empty($select))
      {
        $errorMsg[]="Request ID entered already exist. Please choose different Request ID!";
      }
      if(empty($request->pid)||empty($request->qty))
      {
        $errorMsg[]="Product cannot empty!";
      }
      $reqitem = [];
      for($i = 0; $i < sizeof($request->qty); $i++)
      {
        if($request->qty[$i] > 0)
          array_push($reqitem, ["request_id" => $request->rid, "product_id" => $request->pid[$i], "qty" => $request->qty[$i] ]);
      }
      if(empty($reqitem))
      {
        $errorMsg[]="Product cannot empty!";
      }
      //If no error
      if(empty($errorMsg))
      {
        $data = [
          "request_id" => $request->rid,
          "status" => $request->status,
          "created_date" => DB::raw("sysdate"),
          "created_by" => session("empid"),
          "modified_date" => DB::raw("sysdate"),
          "modified_by" => session("empid"),
        ];
        DB::connection("oracle")->table("requests")->insert($data);
        foreach($reqitem as $item)
        {
          DB::connection("oracle")->table("request_item")->insert($item);
        }
        return response()->json([
          "success" => true,
          "response" => "Request successfully added!"
        ]);
      }

      //Return for custom validation
      return response()->json([
        "success" => false,
        "response" => $errorMsg,
      ]);
    }

    public function requestedit(Request $request)
    {
      $req = UserFunction::odecrypt($request->q);
      $requests = DB::connection("oracle")->select("SELECT r.request_id, r.status,
      i.product_id, i.category, i.brand, i.model, b.qty, i.original_cost, b.qty*i.original_cost total,
      TO_CHAR(r.created_date,'DD-MM-YYYY HH:MI AM') as fmcreated_date,
      TO_CHAR(r.modified_date,'DD-MM-YYYY HH:MI AM') as fmmodified_date,
      u2.nickname createdby, u3.nickname modifiedby
      FROM requests r
      INNER JOIN request_item b ON b.request_id = r.request_id
      INNER JOIN inventories i ON i.product_id = b.product_id
      INNER JOIN users u2 on u2.emp_id = r.created_by
      INNER JOIN users u3 on u3.emp_id = r.modified_by
      WHERE r.request_id = ? ", [$req->rid]);
      return view("requestedit")->with("requests", $requests);
    }

    public function requestedit_post(Request $request)
    {
      $rid = UserFunction::decrypt($request->encrid);
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
        $affected = DB::connection("oracle")->table("requests")->where("request_id", $rid)->update($data);
        if($affected == 0)
        {
          $response = "No change detected. No record updated!" ;
        }
        else
        {
          $response = "Request successfully updated!";
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

    public function requestdel(Request $request)
    {
      $delid = UserFunction::odecrypt($request->delid);
      $affected = DB::connection("oracle")->table("request_item")->where("request_id", $delid->rid)->delete();
      $affected = DB::connection("oracle")->table("requests")->where("request_id", $delid->rid)->delete();
      if($affected > 0)
      {
        return response()->json([
          "success" => true,
          "response" => "Request record successfully deleted!",
        ]);
      }

      return response()->json([
        "success" => false,
        "response" => ["Record not found. No delete!"],
      ]);
    }
}
