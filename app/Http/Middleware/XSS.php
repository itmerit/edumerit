<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'POST' || $request->method() == 'PUT') {
            $input = $request->all();
            array_walk_recursive($input, function (&$input) {
                if ($input) {
                    $str = $input;
                    $searchVal = array("<script>", "</script>");
                    $replaceVal = array(" ", " ");
                    $input = str_replace($searchVal, $replaceVal, $str);
                }
            });
            $request->merge($input);
            return $next($request);

        } else {
            $input = $request->all();
            array_walk_recursive($input, function (&$input) {
                if ($input) {
                    $input = htmlentities($input);
                }
            });
            $request->merge($input);
            return $next($request);
        }
    }
}
