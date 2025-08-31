<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // $route = $request->route()->getName();

        // // $routename = $request->route()->getPrefix();
        // // $routename = $request->route()->getActionName();

        // $name = explode('.',$route);
        // $routename = $name[0];

        // $rolename = $routename."-".$role;

        // $access = Auth::user()->roles()->first()->accesskeuangans()->get();
        // $acc = array();
        // foreach ($access as $value) {
        //   $acc[] = $value->name;
        // }
        // if (Auth::guest()){
        //   return Redirect('login');
        // }

        // if (in_array($rolename,$acc)) {
        //   return $next($request);
        // }else {
          
        //   return Response(view('error/403'));
        // }
        $route = $request->route()->getName();

        $name = explode('.',$route);
        $routename = $name[0];

        $rolename = $routename."-".$role;

        $access = auth()->user()->akses();
          $acc = $access;

        if (Auth::guest()){
          return Redirect('login');
        }

        if (in_array($rolename,$acc)) {
          return $next($request);
        }else {
          return Response(view('403'));
        }

    }
}
