<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->check()) {
            if (Auth::user()->role_id == 1)
                return redirect()->route('admin-dashboard');
            elseif (Auth::user()->role_id == 2)
                return redirect()->route('student-dashboard');
            elseif (Auth::user()->role_id == 3)
                return redirect()->route('parent-dashboard');
            elseif (Auth::user()->role_id == 4)
                return redirect()->route('admin-dashboard');
            elseif (Auth::user()->role_id == 5)
                return redirect()->route('admin-dashboard');
            elseif (Auth::user()->role_id == 6)
                return redirect()->route('parent-dashboard');
            elseif (Auth::user()->role_id == 7)
                return redirect()->route('admin-dashboard');
            else {
                return redirect()->back();
            }
        }
//        } elseif(!Auth::guard($guard)->check()) {
//            return redirect()->route('https://iftixormaktabi.uz');
//        }

        return $next($request);
    }
}
