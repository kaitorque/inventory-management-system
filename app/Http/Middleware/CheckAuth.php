<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
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
          $select2 = DB::connection("oracle")->select("SELECT * FROM staffs WHERE emp_id = ?", [session('empid')]);
          if(empty($select2))
          {
            session(['usertype' => "manager"]);
          }
          else
          {
            session(['usertype' => "staff"]);
          }
          session(['nickname' => $select[0]->nickname]);
          session(['firstname' => $select[0]->first_name]);
          session(['lastname' => $select[0]->last_name]);
          return $next($request);
        }
      }
    }
}
