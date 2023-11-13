<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;
use App\User;

class CheckDashboardMiddleware
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
        if ( !Auth::guest() && Auth::user()->access_status == 0) {
            Auth::logout();
        }

//        session_start();
        $role_id = Session::get('role_id');

        if ($role_id == 2) {
            // return $next($request);
            return redirect('student-dashboard');
        } elseif ($role_id != '') {
            return $next($request);
        } else {
            return redirect('login');
        }
    }
}
