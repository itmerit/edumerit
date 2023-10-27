<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        return route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {

        if ( Auth::user()->access_status == 0) {
            Auth::logout();
        }
        return $next($request);
    }
}
