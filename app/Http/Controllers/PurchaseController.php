<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use DB;
use UserFunction;

class PurchaseController extends Controller
{
    public function __construct()
    {
      $this->middleware('checkauth');
    }

    public function purchaselist(Request $request)
    {
      $purchase = DB::connection('oracle')->select("SELECT p.purchases_id, TO_CHAR(p.created_date,'DD-MM-YYYY HH:MI AM') as fmmodified_date, NVL(u.nickname, u.emp_id) as ncreated_by,
         SUM(s.quantity * i.original_cost) totalcost, SUM(s.quantity * (s.standard_price-s.discount_price)) totalsales, SUM(s.quantity * (s.standard_price-s.discount_price))-SUM(s.quantity * i.original_cost) totalprofit
        FROM purchases p
        INNER JOIN sales s on s.purchases_id = p.purchases_id
        INNER JOIN inventories i on i.product_id = s.product_id
        INNER JOIN users u on u.emp_id = p.modified_by
        GROUP BY p.purchases_id, TO_CHAR(p.created_date,'DD-MM-YYYY HH:MI AM'), NVL(u.nickname, u.emp_id), p.created_date
        ORDER BY p.created_date DESC");
      $purchase = array_map(function($row){
        $row->link = UserFunction::encrypt("puid={$row->purchases_id}");
        return $row;
      },$purchase);
      if ($request->isMethod('get'))
      {
        return view("purchaselist");
      }
      else if($request->isMethod('post'))
      {
        return response()->json([
          "success" => true,
          "response" => "Search List Successful",
          "data" => $purchase,
          "link" => route("purchaseedit"),
        ]);
      }
    }

    public function purchaseadd()
    {
      return view("purchaseadd");
    }

    public function purchaseadd_post(Request $request)
    {
      //Declare custom error message for custom validator
      $errorMsg = [];
      //Using laravel validator for input
      $rules = [
      'puid' => 'required|numeric',
      ];
      $message = [
        'puid.required' => 'Purchases ID is required.',
        'puid.numeric' => 'Purchases ID must be numeric',
      ];
      $validator = Validator::make(Input::all(), $rules, $message);
      if($validator->fails()) {
        return response()->json([
          "success" => false,
          "response" => $validator->errors()->all(),
        ]);
      }
      //Custom Validation
      $select = DB::connection("oracle")->select("SELECT * FROM purchases WHERE purchases_id = ? ", [$request->puid]);
      if(!empty($select))
      {
        $errorMsg[]="Purchases ID entered already exist. Please choose different Purchases ID!";
      }
      if(empty($request->pid)||empty($request->qty)||empty($request->price)||empty($request->tax)||empty($request->discount))
      {
        $errorMsg[]="Product cannot empty!";
      }
      $reqitem = [];
      for($i = 0; $i < sizeof($request->qty); $i++)
      {
        if($request->qty[$i] > 0)
          array_push($reqitem, [
            "purchases_id" => $request->puid,
            "product_id" => $request->pid[$i],
            "quantity" => $request->qty[$i],
            "standard_price" => ( empty($request->price[$i]) ? 0 : $request->price[$i] ),
            "tax" => ( empty($request->tax[$i]) ? 0 : $request->tax[$i] ),
            "discount_price" => ( empty($request->discount[$i]) ? 0 : $request->discount[$i] ),
           ]);
      }
      if(empty($reqitem))
      {
        $errorMsg[]="Product cannot empty!";
      }
      //If no error
      if(empty($errorMsg))
      {
        $data = [
          "purchases_id" => $request->puid,
          "description" => (empty($request->desc) ? DB::raw("NULL") : $request->desc),
          "created_date" => DB::raw("sysdate"),
          "created_by" => session("empid"),
          "modified_date" => DB::raw("sysdate"),
          "modified_by" => session("empid"),
        ];
        DB::connection("oracle")->table("purchases")->insert($data);
        foreach($reqitem as $item)
        {
          DB::connection("oracle")->table("sales")->insert($item);
          $update = [
            "quantity" => DB::raw("quantity-{$item['quantity']}")
          ];
          DB::connection("oracle")->table("inventories")->where("product_id", $item['product_id'])->update($update);
        }
        return response()->json([
          "success" => true,
          "response" => "Purchase successfully added!"
        ]);
      }

      //Return for custom validation
      return response()->json([
        "success" => false,
        "response" => $errorMsg,
      ]);
    }

    public function purchaseedit(Request $request)
    {
      $req = UserFunction::odecrypt($request->q);
      $purchase = DB::connection("oracle")->select("SELECT p.purchases_id, p.description,
      i.product_id, i.category, i.brand, i.model, s.quantity, s.standard_price, s.tax, s.discount_price, (s.quantity*(s.standard_price-s.discount_price))*(s.tax+100)/100 total,
      TO_CHAR(p.created_date,'DD-MM-YYYY HH:MI AM') as fmcreated_date,
      TO_CHAR(p.modified_date,'DD-MM-YYYY HH:MI AM') as fmmodified_date,
      u2.nickname createdby, u3.nickname modifiedby
      FROM purchases p
      INNER JOIN sales s ON s.purchases_id = p.purchases_id
      INNER JOIN inventories i ON i.product_id = s.product_id
      INNER JOIN users u2 on u2.emp_id = p.created_by
      INNER JOIN users u3 on u3.emp_id = p.modified_by
      WHERE p.purchases_id = ? ", [$req->puid]);
      return view("purchaseedit")->with("purchase", $purchase);
    }

    public function purchaseedit_post(Request $request)
    {
      $puid = UserFunction::decrypt($request->encpuid);
      //Declare custom error message for custom validator
      $errorMsg = [];
      //Using laravel validator for input
      //Custom Validation
      //If no error
      if(empty($errorMsg))
      {
        $data = [
          "description" => (empty($request->desc) ? DB::raw("NULL") : $request->desc),
          "modified_date" => DB::raw("sysdate"),
          "modified_by" => session("empid"),
        ];
        $affected = DB::connection("oracle")->table("purchases")->where("purchases_id", $puid)->update($data);
        if($affected == 0)
        {
          $response = "No change detected. No record updated!" ;
        }
        else
        {
          $response = "Purchase successfully updated!";
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

    public function purchasedel(Request $request)
    {
      $delid = UserFunction::odecrypt($request->delid);
      $affected = DB::connection("oracle")->table("sales")->where("purchases_id", $delid->puid)->delete();
      $affected = DB::connection("oracle")->table("purchases")->where("purchases_id", $delid->puid)->delete();
      if($affected > 0)
      {
        return response()->json([
          "success" => true,
          "response" => "Purchases record successfully deleted!",
        ]);
      }

      return response()->json([
        "success" => false,
        "response" => ["Record not found. No delete!"],
      ]);
    }

    public function purchasequick(Request $request)
    {
      $errorMsg = [];
      if(empty($request->productid))
      {
        $errorMsg[] = "Please enter Product ID";
      }
      else
      {
        $select = DB::connection("oracle")->select("SELECT product_id, category, brand, model, original_cost, retail_price
        FROM inventories WHERE product_id = ? ", [$request->productid]);
        if(empty($select))
        {
          $errorMsg[] = "No Product found";
        }
      }
      if(empty($errorMsg))
      {
        return response()->json([
          "success" => true,
          "response" => "Product found",
          "data" => $select[0],
        ]);
      }
      //Return for custom validation
      return response()->json([
        "success" => false,
        "response" => $errorMsg,
      ]);
    }
}
