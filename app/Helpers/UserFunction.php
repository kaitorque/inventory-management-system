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
      // return redirect()->route('login');
      if(empty(session("empid")))
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
          $select2 = DB::connection("oracle")->select("SELECT * FROM staff WHERE staff_id = ?", [session('empid')]);
          if(empty($select2))
          {
            session(['usertype' => "manager"]);
          }
          else
          {
            session(['usertype' => "staff"]);
          }
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

    public static function buildcbsort($cbname, $option, $value, $match, $style)
    {
      $optionArr = explode(",", $option);
      $valueArr = explode(",", $value);
      $string = "<select id='{$cbname}' name='{$cbname}' class='{$style}'>";
      for($i=0; $i<count($optionArr); $i++)
      {
        if($valueArr[$i] == $match)
        {
          $string .= "<option selected value='{$valueArr[$i]}'>{$optionArr[$i]}</option>";
        }
        else
        {
          $string .= "<option value='{$valueArr[$i]}'>{$optionArr[$i]}</option>";
        }
      }
      $string .= "</select>";
      return $string;
    }
}
