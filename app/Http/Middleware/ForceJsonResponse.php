<?php

namespace App\Http\Middleware;

use Closure;
use App\ApiBaseMethod;

class ForceJsonResponse
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
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $request->headers->set('Accept', 'application/json');
            return $next($request);
        }else{
            return $next($request);
        }
    }
}
