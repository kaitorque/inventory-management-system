<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use DB;
use UserFunction;
use DateTime;

class HomeController extends Controller
{
    public function __construct()
    {
      $this->middleware('checkauth');
    }

    public function home()
    {
      return view('home');
    }

    public function userlist(Request $request)
    {
      $users = DB::connection('oracle')->select("SELECT u.*, TO_CHAR(u.created_date,'DD-MM-YYYY HH:MI AM') as fmcreated_date,
      NVL(u2.nickname, u2.emp_id) as ncreated_by
      FROM users u INNER JOIN users u2 ON u.modified_by = u2.emp_id");
      $users = array_map(function($row){
        $row->link = UserFunction::encrypt("empid={$row->emp_id}");
        return $row;
      },$users);
      if ($request->isMethod('get'))
      {
        return view("userlist")->with("users", $users);
      }
      else if($request->isMethod('post'))
      {
        return response()->json([
          "success" => true,
          "response" => "Search List Successful",
          "data" => $users,
          "link" => route("useredit"),
        ]);
      }
    }

    public function useradd()
    {
      return view("useradd");
    }

    public function useradd_post(Request $request)
    {
      //Declare custom error message for custom validator
      $errorMsg = [];
      //Using laravel validator for input
      $rules = [
      'empid' => 'required|numeric',
      'usertype' => 'required',
      'fname' => 'required',
      'lname' => 'required',
      'nname' => 'required|regex:/^[\w-]*$/',
      'dob' => 'required',
      'address1' => 'required',
      'city' => 'required',
      'state' => 'required',
      'zipcode' => 'required',
      'maritalstatus' => 'required',
      'pass' => 'required',
      'cpass' => 'required',
      'ssn' => 'required|numeric',
      ];
      $message = [
        'empid.required' => 'Employee ID is required.',
        'empid.numeric' => 'Employee ID must be numeric',
        'usertype.required' => 'Type is required.',
        'fname.required' => 'First Name is required.',
        'lname.required' => 'Last Name is required.',
        'nname.required' => 'Nickname is required.',
        'nname.regex' => 'Nickname must be alphanumeric only with no space.',
        'dob.required' => 'Date of Birth is required.',
        'address1.required' => 'Address is required.',
        'city.required' => 'City is required.',
        'state.required' => 'State is required.',
        'zipcode.required' => 'Zip Code is required.',
        'maritalstatus.required' => 'Marital Status is required.',
        'pass.required' => 'Password is required.',
        'cpass.required' => 'Confirm Password is required.',
        'ssn.required' => 'IC Number is required.',
        'ssn.numeric' => 'IC Number must be numeric',
      ];
      $validator = Validator::make(Input::all(), $rules, $message);
      if($validator->fails()) {
        return response()->json([
          "success" => false,
          "response" => $validator->errors()->all(),
        ]);
      }
      //Custom Validation
      if($request->usertype == "staff")
      {
        if(empty($request->dept))
        {
          $errorMsg[]="Department is required.";
        }
        if(empty($request->parttime))
        {
          $errorMsg[]="Part Time is required.";
        }
      }
      $select = DB::connection("oracle")->select("SELECT * FROM USERS WHERE EMP_ID = ? ", [$request->empid]);
      if(!empty($select))
      {
        $errorMsg[]="Employee ID entered already exist. Please choose different Employee id!";
      }
      $select = DB::connection("oracle")->select("SELECT * FROM users WHERE nickname = ? ", [$request->nname]);
      if(!empty($select))
      {
        $errorMsg[]="Nickname entered already exist. Please choose different Nickname!";
      }
      if($request->pass != $request->cpass)
      {
        $errorMsg[]="Confirm password mismatched !";
      }
      if($request->usertype == "" || $request->usertype == "null")
      {
        $errorMsg[]="Please choose Type";
      }
      //If no error
      if(empty($errorMsg))
      {
        if(!empty($request->dob))
          $birthdate=DateTime::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
        else {
          $birthdate = NULL;
        }
        $data = [
          "emp_id" => $request->empid,
          "first_name" => $request->fname,
          "last_name" => $request->lname,
          "nickname" => $request->nname,
          "dob" => $birthdate,
          "password" => Hash::make($request->pass),
          "street_add1" => $request->address1,
          "street_add2" => $request->address2,
          "city" => $request->city,
          "state" => $request->state,
          "zip_code" => $request->zipcode,
          "marital_status" => $request->maritalstatus,
          "ssn" => $request->ssn,
          "created_date" => DB::raw("sysdate"),
          "created_by" => session("empid"),
          "modified_date" => DB::raw("sysdate"),
          "modified_by" => session("empid"),
        ];
        DB::connection("oracle")->table("users")->insert($data);
        if($request->usertype == "staff")
        {
          $data2 = [
            "staff_id" => $request->empid,
            "dept" => $request->dept,
            "part_time" => $request->parttime,
          ];
          DB::connection("oracle")->table("staff")->insert($data2);
        }
        else {
          $data2 = [
            "mgr_id" => $request->empid,
          ];
          DB::connection("oracle")->table("managers")->insert($data2);
        }
        return response()->json([
          "success" => true,
          "response" => "User successfully added!"
        ]);
      }

      //Return for custom validation
      return response()->json([
        "success" => false,
        "response" => $errorMsg,
      ]);
    }

    public function useredit(Request $request)
    {
      $req = UserFunction::odecrypt($request->q);
      $user = DB::connection("oracle")->select("SELECT u.*, TO_CHAR(dob, 'DD/MM/YYYY') fmtdob FROM users u WHERE emp_id = ? ", [$req->empid]);
      $select = DB::connection("oracle")->select("SELECT * FROM staff WHERE staff_id = ?", [$req->empid]);
      if(empty($select))
      {
        $user[0]->usertype = "manager";
        $user[0]->dept = "";
        $user[0]->part_time = "";
      }
      else
      {
        $user[0]->usertype = "staff";
        $user[0]->dept = $select[0]->dept;
        $user[0]->part_time = $select[0]->part_time;
      }
      return view("useredit")->with("user", $user[0]);
    }

    public function useredit_post(Request $request)
    {
      $empid = UserFunction::decrypt($request->encempid);
      //Declare custom error message for custom validator
      $errorMsg = [];
      //Using laravel validator for input
      $rules = [
      'usertype' => 'required',
      'fname' => 'required',
      'lname' => 'required',
      'nname' => 'required|regex:/^[\w-]*$/',
      'dob' => 'required',
      'address1' => 'required',
      'city' => 'required',
      'state' => 'required',
      'zipcode' => 'required',
      'maritalstatus' => 'required',
      'ssn' => 'required|numeric',
      ];
      $message = [
        'usertype.required' => 'Type is required.',
        'fname.required' => 'First Name is required.',
        'lname.required' => 'Last Name is required.',
        'nname.required' => 'Nickname is required.',
        'nname.regex' => 'Nickname must be alphanumeric only with no space.',
        'dob.required' => 'Date of Birth is required.',
        'address1.required' => 'Address is required.',
        'city.required' => 'City is required.',
        'state.required' => 'State is required.',
        'zipcode.required' => 'Zip Code is required.',
        'maritalstatus.required' => 'Marital Status is required.',
        'ssn.required' => 'IC Number is required.',
        'ssn.numeric' => 'IC Number must be numeric',
      ];
      $validator = Validator::make(Input::all(), $rules, $message);
      if($validator->fails()) {
        return response()->json([
          "success" => false,
          "response" => $validator->errors()->all(),
        ]);
      }
      //Custom Validation
      if($request->usertype == "staff")
      {
        if(empty($request->dept))
        {
          $errorMsg[]="Department is required.";
        }
        if(empty($request->parttime))
        {
          $errorMsg[]="Part Time is required.";
        }
      }
      $select = DB::connection("oracle")->select("SELECT * FROM users WHERE nickname = ? AND emp_id != ? ", [$request->nname, $empid]);
      if(!empty($select))
      {
        $errorMsg[]="Nickname entered already exist. Please choose different Nickname!";
      }
      if(!empty($request->pass)|| !empty($request->cpass))
      {
        if($request->pass != $request->cpass)
        {
          $errorMsg[]="Confirm password mismatched !";
        }
        else {
          $changepass = [ "password" => Hash::make($request->pass) ];
        }
      }
      if($request->usertype == "" || $request->usertype == "null")
      {
        $errorMsg[]="Please choose Type";
      }
      //If no error
      if(empty($errorMsg))
      {
        if(!empty($request->dob))
          $birthdate=DateTime::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
        else {
          $birthdate = NULL;
        }
        $data = [
          "first_name" => $request->fname,
          "last_name" => $request->lname,
          "nickname" => $request->nname,
          "dob" => $birthdate,
          "street_add1" => $request->address1,
          "street_add2" => $request->address2,
          "city" => $request->city,
          "state" => $request->state,
          "zip_code" => $request->zipcode,
          "marital_status" => $request->maritalstatus,
          "ssn" => $request->ssn,
          "modified_date" => DB::raw("sysdate"),
          "modified_by" => session("empid"),
        ];
        if(!empty($changepass))
        {
          $data = array_merge($data, $changepass);
        }
        $affected = DB::connection("oracle")->table("users")->where("emp_id", $empid)->update($data);
        $select = DB::connection("oracle")->select("SELECT * FROM staff WHERE staff_id = ?", [$empid]);
        if(empty($select))
        {
          $usertype = "manager";
        }
        else
        {
          $usertype = "staff";
        }
        if($usertype != $request->usertype)
        {
          if($request->usertype == "manager")
          {
            $delete = DB::connection("oracle")->table("staff")->where("staff_id", $empid)->delete();
            $data2 = [ "mgr_id" => $empid ];
            $insert = DB::connection("oracle")->table("managers")->insert($data2);
          }
          else {
            $delete = DB::connection("oracle")->table("managers")->where("mgr_id", $empid)->delete();
            $data2 = [
              "staff_id" => $empid,
              "dept" => $request->dept,
              "part_time" => $request->parttime,
            ];
            $insert = DB::connection("oracle")->table("staff")->insert($data2);
          }
        }
        else
        {
          if($request->usertype == "staff")
          {
            $data2 = [
              "dept" => $request->dept,
              "part_time" => $request->parttime,
            ];
            $affected = DB::connection("oracle")->table("staff")->where("staff_id", $empid)->update($data2);
          }
        }
        if($affected == 0 && $affected2 == false)
        {
          $response = "No change detected. No record updated!" ;
        }
        else
        {
          $response = "User successfully updated!";
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

    public function userdel(Request $request)
    {
      $delid = UserFunction::odecrypt($request->delid);
      $affected = DB::connection("oracle")->table("managers")->where("mgr_id", $delid->empid)->delete();
      $affected = DB::connection("oracle")->table("staff")->where("staff_id", $delid->empid)->delete();
      $affected = DB::connection("oracle")->table("users")->where("emp_id", $delid->empid)->delete();
      if($affected > 0)
      {
        return response()->json([
          "success" => true,
          "response" => "User record successfully deleted!",
        ]);
      }

      return response()->json([
        "success" => false,
        "response" => ["Record not found. No delete!"],
      ]);
    }
}
