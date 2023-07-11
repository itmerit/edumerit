<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;

class HttpsProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (env('FORCE_HTTPS')) {
            if (!$request->secure() && App::environment() === 'production') {
                $currentURL = Request::url();
                $check = strstr($currentURL, "http://");
                if ($check) {
                    $currentURL = str_replace("http", "https", $currentURL);
                    return redirect()->to($currentURL);
                }

            }
        }
        return $next($request);
    }
}
