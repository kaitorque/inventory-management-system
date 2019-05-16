<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use DB;
use UserFunction;

class HomeController extends Controller
{
    public function __construct()
    {
      UserFunction::checkAuth();
    }

    public function home()
    {
      return view('home');
    }

    public function userlist(Request $request)
    {
      $users = DB::connection('oracle')->select("SELECT u.*, TO_CHAR(u.created_date,'DD-MM-YYYY HH:MI AM') as fmcreated_date, NVL(u2.nickname, u2.emp_id) as ncreated_by FROM users u LEFT JOIN users u2 ON u2.modified_by = u.emp_id");

      return view("userlist")->with("users", $users);
    }

    public function useradd()
    {
      return view("useradd");
    }
}
