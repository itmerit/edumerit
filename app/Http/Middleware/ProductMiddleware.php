<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\User;
use App\Envato\Envato;
use GuzzleHttp\Client;
use App\SmGeneralSettings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class ProductMiddleware
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
        return $next($request);
    }
}
