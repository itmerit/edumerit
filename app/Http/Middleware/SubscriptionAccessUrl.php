<?php

namespace App\Http\Middleware;

use Closure;
use Brian2694\Toastr\Facades\Toastr;

class SubscriptionAccessUrl
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

        if(isSubscriptionEnabled()){
            $temp = \Modules\Saas\Entities\SmPackagePlan::isSubscriptionAutheticate();
            if ($temp == true) {
                return $next($request);
            }else{
                return redirect('subscription/package-list');
            }
        }else{
            return $next($request);
        }
    }
}