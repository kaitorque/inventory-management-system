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
      $requestlist = DB::connection('oracle')->select("SELECT UNIQUE A.REQUEST_ID, TO_CHAR(A.CREATED_DATE,'fmDD Month YYYY') AS DATES, D.NICKNAME,A.req_status FROM ORDER_REQUEST A JOIN ITEM B ON A.REQUEST_ID = B.REQ_REQUEST_ID JOIN INVENTORIES C ON B.INV_PRODUCT_ID=C.PRODUCT_ID JOIN USERS D ON a.usr_emp_id=d.emp_id ORDER BY TO_DATE(DATES,'fmDD Month YYYY')");
      return view("requestlist")->with("requestlist", $requestlist);
    }

    public function requestview(Request $request)
    {

      $reqid_ = 10;
      $requestview = DB::connection('oracle')->select("SELECT * FROM ORDER_REQUEST A JOIN ITEM B ON a.request_id = b.req_request_id JOIN INVENTORIES C ON b.inv_product_id=c.product_id where a.request_id=$reqid_");
      return view("requestview")->with("requestview", $requestview);
    }

    public function retrieveID(Request $request)
    {
      $reqid=10;
      return view("requestview")->with("retrieveID", $reqid);
    }

    public function sumqty(Request $request)
    {

      $sumqty = DB::connection('oracle')->select("SELECT A.REQUEST_ID, SUM(B.QTY) AS TOTAL FROM order_request A JOIN ITEM B ON a.request_id=b.req_request_id GROUP BY A.REQUEST_ID");
      return view("requestview")->with("sumqty", $sumqty);
    }

    /*public function useradd()
    {
      return view("useradd");
    }*/
}
