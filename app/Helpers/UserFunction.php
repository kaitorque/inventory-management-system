<?php

namespace App\Helpers;
use DB;

class UserFunction
{
  // To call the function: UserFunction::testHello();
    public static function testHello()
    {
      session(['test' => 'Hi!!!']);
      return "Hi!!!";
    }

    public static function checkAuth()
    {
      if(!session()->has('empid'))
      {
        return redirect()->route('login');
      }
      else
      {
        $select = DB::connection('oracle')->select("SELECT * FROM USERS WHERE EMP_ID = ?", [session('empid')]);
        if(empty($select))
        {
          return redirect()->route('login');
        }
        else
        {
            session(['nickname' => $select[0]->nickname]);
        }
      }
    }
}
