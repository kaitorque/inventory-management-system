<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;

class TestController extends Controller
{
    public function test()
    {
      $user = DB::connection('oracle')->select("SELECT * FROM TEST");
      return view('test')->with("user", $user);
    }

}
