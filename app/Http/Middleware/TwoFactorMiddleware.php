<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\TwoFactorAuth\Entities\TwoFactorSetting;

class TwoFactorMiddleware
{
    
    public function handle(Request $request, Closure $next)
    {
        if(moduleStatusCheck('TwoFactorAuth') && generalSetting()->two_factor){
            if(!auth()->check()){
                return $next($request);
            }
            $school = SaasSchool();
            $setting = TwoFactorSetting::where('school_id', $school->id)->first();
            $role_id = auth()->user()->role_id;
            $role_ids = [1,2,3,4,5];
           
            if($setting->for_student == $role_id){
                if (!Session::has('user_2fa')) {
                    return redirect()->route('2fa.index');
                }
            }
            elseif($setting->for_parent == $role_id){
                if (!Session::has('user_2fa')) {
                    return redirect()->route('2fa.index');
                }
            }
            elseif($setting->for_teacher == $role_id ){
                if (!Session::has('user_2fa')) {
                    return redirect()->route('2fa.index');
                }
            }
            elseif($setting->for_admin == $role_id ){
                if (!Session::has('user_2fa')) {
                    return redirect()->route('2fa.index');
                }
            }
            
            elseif($setting->for_staff && (!in_array($role_id,$role_ids))){
                if (!Session::has('user_2fa')) {
                    return redirect()->route('2fa.index');
                }
            }
            return $next($request);
        }
        return $next($request);
    }
}
