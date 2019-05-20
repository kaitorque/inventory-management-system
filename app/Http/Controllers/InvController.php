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
      $this->middleware('checkauth');
    }

    public function inventorylist(Request $request)
    {
      $inventory = DB::connection('oracle')->select("SELECT i.product_id, i.category, i.brand, i.model, i.quantity, TO_CHAR(i.created_date,'DD-MM-YYYY HH:MI AM') as fmmodified_date,
        NVL(u.nickname, u.emp_id) as ncreated_by
        FROM inventories i INNER JOIN users u ON u.emp_id = i.modified_by");
      $inventory = array_map(function($row){
        $row->link = UserFunction::encrypt("pid={$row->product_id}");
        return $row;
      },$inventory);
      if ($request->isMethod('get'))
      {
        return view("inventorylist");
      }
      else if($request->isMethod('post'))
      {
        return response()->json([
          "success" => true,
          "response" => "Search List Successful",
          "data" => $inventory,
          "link" => route("inventoryedit"),
        ]);
      }
    }

    public function inventoryadd()
    {
      return view("inventoryadd");
    }

    public function inventoryadd_post(Request $request)
    {
      //Declare custom error message for custom validator
      $errorMsg = [];
      //Using laravel validator for input
      $rules = [
      'pid' => 'required|numeric',
      'cost' => 'required|numeric',
      'price' => 'required|numeric',
      'category' => 'required',
      'brand' => 'required',
      'model' => 'required',
      'warranty' => 'integer|nullable',
      ];
      $message = [
        'pid.required' => 'Employee ID is required.',
        'pid.numeric' => 'Employee ID must be numeric',
        'cost.required' => 'Original Cost is required.',
        'cost.numeric' => 'Original Cost must be numeric',
        'price.required' => 'Retail Price is required.',
        'price.numeric' => 'Retail Price must be numeric',
        'category.required' => 'Category is required.',
        'brand.required' => 'Brand is required.',
        'model.required' => 'Model is required.',
        'warranty.integer' => 'Warranty must be integer',
      ];
      $validator = Validator::make(Input::all(), $rules, $message);
      if($validator->fails()) {
        return response()->json([
          "success" => false,
          "response" => $validator->errors()->all(),
        ]);
      }
      //Custom Validation
      $select = DB::connection("oracle")->select("SELECT * FROM inventories WHERE product_id = ? ", [$request->pid]);
      if(!empty($select))
      {
        $errorMsg[]="Product ID entered already exist. Please choose different Product ID!";
      }
      //If no error
      if(empty($errorMsg))
      {
        $data = [
          "product_id" => $request->pid,
          "original_cost" => $request->cost,
          "retail_price" => $request->price,
          "category" => $request->category,
          "brand" => $request->brand,
          "model" => $request->model,
          "groups" => (empty($request->groups) ? DB::raw("NULL") : $request->groups),
          "quantity" => 0,
          "warranty_month" => (empty($request->warranty) ? 0 : $request->warranty),
          "description" => (empty($request->desc) ? DB::raw("NULL") : $request->desc),
          "created_date" => DB::raw("sysdate"),
          "created_by" => session("empid"),
          "modified_date" => DB::raw("sysdate"),
          "modified_by" => session("empid"),
        ];
        DB::connection("oracle")->table("inventories")->insert($data);
        return response()->json([
          "success" => true,
          "response" => "Product successfully added!"
        ]);
      }

      //Return for custom validation
      return response()->json([
        "success" => false,
        "response" => $errorMsg,
      ]);
    }

    public function inventoryedit(Request $request)
    {
      $req = UserFunction::odecrypt($request->q);
      $product = DB::connection("oracle")->select("SELECT i.product_id, i.quantity, i.original_cost, i.retail_price, i.category,
      i.brand, i.model, i.groups, i.warranty_month, i.description,
      TO_CHAR(i.created_date,'DD-MM-YYYY HH:MI AM') as fmcreated_date,
      TO_CHAR(i.modified_date,'DD-MM-YYYY HH:MI AM') as fmmodified_date,
      u2.nickname createdby, u3.nickname modifiedby
      FROM inventories i
      INNER JOIN users u2 on u2.emp_id = i.created_by
      INNER JOIN users u3 on u3.emp_id = i.modified_by
      WHERE i.product_id = ? ", [$req->pid]);
      return view("inventoryedit")->with("product", $product[0]);
    }

    public function inventoryedit_post(Request $request)
    {
      $pid = UserFunction::decrypt($request->encpid);
      //Declare custom error message for custom validator
      $errorMsg = [];
      //Using laravel validator for input
      $rules = [
      'cost' => 'required|numeric',
      'price' => 'required|numeric',
      'category' => 'required',
      'brand' => 'required',
      'model' => 'required',
      'warranty' => 'integer|nullable',
      ];
      $message = [
        'cost.required' => 'Original Cost is required.',
        'cost.numeric' => 'Original Cost must be numeric',
        'price.required' => 'Retail Price is required.',
        'price.numeric' => 'Retail Price must be numeric',
        'category.required' => 'Category is required.',
        'brand.required' => 'Brand is required.',
        'model.required' => 'Model is required.',
        'warranty.integer' => 'Warranty must be integer',
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
          "original_cost" => $request->cost,
          "retail_price" => $request->price,
          "category" => $request->category,
          "brand" => $request->brand,
          "model" => $request->model,
          "groups" => (empty($request->groups) ? DB::raw("NULL") : $request->groups),
          "warranty_month" => (empty($request->warranty) ? 0 : $request->warranty),
          "description" => (empty($request->desc) ? DB::raw("NULL") : $request->desc),
          "modified_date" => DB::raw("sysdate"),
          "modified_by" => session("empid"),
        ];
        $affected = DB::connection("oracle")->table("inventories")->where("product_id", $pid)->update($data);
        if($affected == 0)
        {
          $response = "No change detected. No record updated!" ;
        }
        else
        {
          $response = "Product successfully updated!";
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

    public function inventorydel(Request $request)
    {
      $delid = UserFunction::odecrypt($request->delid);
      $affected = DB::connection("oracle")->table("inventories")->where("product_id", $delid->pid)->delete();
      if($affected > 0)
      {
        return response()->json([
          "success" => true,
          "response" => "Product record successfully deleted!",
        ]);
      }

      return response()->json([
        "success" => false,
        "response" => ["Record not found. No delete!"],
      ]);
    }
}
