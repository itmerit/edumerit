<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\User;
use Illuminate\Support\Facades\Session;

class ParentMiddleware
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

        if (User::checkAuth() == false || User::checkAuth() == null) {
            return redirect()->route('system.config');
        }
        session_start();
        $role_id = Session::get('role_id');

        if ($role_id == 3) {
            return $next($request);
        } elseif ($role_id != "") {
            return redirect('parent-dashboard');
        } else {
            return redirect('login');
        }
    }
}
