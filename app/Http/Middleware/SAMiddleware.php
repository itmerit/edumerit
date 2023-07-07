<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class SAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       
        // session_start();
        $role_id = Session::get('role_id');

        if ($role_id == 1 || $role_id == 4 || $role_id == 5 || $role_id == 6 || $role_id == 7 || $role_id == 8 || $role_id == 9) {
            return $next($request);
        }elseif($role_id != ""){
            return redirect('dashboard');
        } else {
            return redirect('login');
        }
    }
}
