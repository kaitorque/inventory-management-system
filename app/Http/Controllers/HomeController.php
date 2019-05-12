<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;
use Request;
use UserFunction;

class HomeController extends Controller
{
    public function __construct()
    {
      UserFunction::testHello();

    }

    public function home()
    {
      return view('empty');
    }
}
