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

    public static function encrypt($data, $key = "")
    {
      if($key == "")
      {
        $key = session('_token');
      }
      $encrypted = openssl_encrypt( $data, "AES-128-ECB" , $key);
      return str_replace(array('+', '/'), array('-', '_'), $encrypted);
    }

    public static function decrypt($data, $key = "")
    {
      if($key == "")
      {
        $key = session('_token');
      }
      $decrypted = str_replace(array('-', '_'), array('+', '/'), $data);
      return openssl_decrypt( $decrypted, "AES-128-ECB" , $key);
    }

    public static function odecrypt($data, $key = "")
    {
      if($key == "")
      {
        $key = session('_token');
      }
      $decrypted = str_replace(array('-', '_'), array('+', '/'), $data);
      parse_str(openssl_decrypt( $decrypted, "AES-128-ECB" , $key), $req);
      $req = (object) $req;
      return $req;
    }
}
