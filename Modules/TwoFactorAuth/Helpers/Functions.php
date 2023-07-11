<?php

use Illuminate\Support\Facades\Session;
use Modules\TwoFactorAuth\Entities\TwoFactorSetting;


function twoFactorCheckup(){

     $setting = TwoFactorSetting::where('school_id', Auth::user()->school_id)->first();
     $role_id = auth()->user()->role_id;
     $role_ids = [1,2,3,4,5];
     
     if($setting->is_student && $role_id == 2){
         if (!Session::has('user_2fa')) {
             return true;
         }
     }
     elseif($setting->is_parent && $role_id == 3){
         if (!Session::has('user_2fa')) {
            return true;
         }
     }
     elseif($setting->is_teacher && $role_id == 4){
         if (!Session::has('user_2fa')) {
            return true;
         }
     }
     elseif($setting->is_admin && $role_id == 1 || 5){
         if (!Session::has('user_2fa')) {
            return true;
         }
     }
     elseif($setting->is_staff && (!in_array($role_id,$role_ids))){
         if (!Session::has('user_2fa')) {
            return true;
         }
     }
}
