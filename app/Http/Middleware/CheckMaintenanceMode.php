<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\MaintenanceSetting;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
   protected $except = [
        'login','login/*','logout'
    ];
    
    protected function inExceptArray($request)
    {

        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
    
    public function handle(Request $request, Closure $next): Response
    {
        
        $c = Storage::exists('.app_installed') ? Storage::get('.app_installed') : false;
        if (!$c) {
            return $next($request);
        }
        if(auth()->user() && auth()->user()->is_administrator =="yes"){
            return $next($request);
        }
  
        if($this->inExceptArray($request)){
            return $next($request);
        }
        
        $school = SaasSchool();
        $setting = MaintenanceSetting::first();   
  
        if($setting && $setting->maintenance_mode){
            if(auth()->check()){
                $check = in_array(auth()->user()->role_id, $setting->applicable_for);
            }else{
                $check = in_array("front", $setting->applicable_for);
            }
            if($check){
                abort(503);
            }
        }
        return $next($request);
    }
}
