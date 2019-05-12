<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use DB;

class LoginController extends Controller
{
    public function login()
    {
      return view('auth.login');
    }

    public function login_post(Request $request)
    {
      //Declare custom error message for custom validator
      $errorMsg = [];
      //Using laravel validator for input
      $rules = [
      'empid' => 'required',
      'password' => 'required',
      ];
      $message = [
        'empid.required' => 'Employee ID is required.',
        'empid.numeric' => 'Employee ID must be numeric',
        'password.required' => 'Password is required.',
      ];
      $validator = Validator::make(Input::all(), $rules, $message);
      if($validator->fails()) {
        return response()->json([
          "success" => false,
          "response" => $validator->errors()->all(),
        ]);
      }
      //Custom Validation
      $select = DB::connection("oracle")->select("SELECT * FROM USERS WHERE EMP_ID = ? ", [$request->empid]);
      if(empty($select))
      {
        $errorMsg[]="User not found";
      }
      else
      {
        if (!Hash::check($request->password, $select[0]->password)) {
          $errorMsg[]="Incorrect Password";
        }
      }
      //If no error
      if(empty($errorMsg))
      {
        session(['empid' => $select[0]->emp_id]);
        $response = "Successfully sign in";
        return response()->json([
          "success" => true,
          "response" => $response,
          "link" => route("home"),
        ]);
      }

      //Return for custom validation
      return response()->json([
        "success" => false,
        "response" => $errorMsg,
      ]);
    }
}