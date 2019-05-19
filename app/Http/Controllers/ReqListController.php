<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use DB;
use UserFunction;

class ReqListController extends Controller
{
    public function __construct()
    {
      UserFunction::checkAuth();
    }



    public function requestlist(Request $request)
    {
      $requestlist = DB::connection('oracle')->select("SELECT UNIQUE A.REQUEST_ID, TO_CHAR(A.CREATED_DATE,'fmDD Month YYYY') AS DATES, D.NICKNAME FROM ORDER_REQUEST A JOIN ITEM B ON A.REQUEST_ID = B.REQ_REQUEST_ID JOIN INVENTORIES C ON B.INV_PRODUCT_ID=C.PRODUCT_ID JOIN USERS D ON a.usr_emp_id=d.emp_id ORDER BY TO_DATE(DATES,'fmDD Month YYYY')");
      return view("requestlist")->with("requestlist", $requestlist);
    }

    /*public function useradd()
    {
      return view("useradd");
    }*/
}
