<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckUserMiddleware
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
        session_start();
        $role_id = Auth::check() ? Session::get('role_id'): null;
        if (!$role_id) {
            return $next($request);
        }else{
            return redirect('dashboard');
        } 
    }
}
