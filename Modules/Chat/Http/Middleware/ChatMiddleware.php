<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Session;
use App\User;

class StudentMiddleware
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

        if ($role_id == 2) {
            return $next($request);
        } elseif ($role_id != "") {
            return redirect('chat.index');
        } else {
            return redirect('login');
        }
    }
}
