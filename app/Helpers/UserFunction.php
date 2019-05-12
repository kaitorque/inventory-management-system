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


}
