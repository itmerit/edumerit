<?php

namespace Modules\TwoFactorAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Modules\TwoFactorAuth\Entities\UserOtpCode;
use Modules\TwoFactorAuth\Entities\TwoFactorSetting;

class TwoFactorAuthController extends Controller
{
    
    public function index()
    {
        $setting = TwoFactorSetting::where('school_id', Auth::user()->school_id)->first();
        return view('twofactorauth::verification');
    }

    public function setting()
    {
        $setting = TwoFactorSetting::where('school_id', Auth::user()->school_id)->first();
        if(is_null($setting)){
            $setting = new TwoFactorSetting();
            $setting->school_id = auth()->user()->school_id;
            $setting->save();
        }
        return view('twofactorauth::two_factor_auth_setting',compact('setting'));
    }

    
    public function verifyCode(Request $request)
    {
        $request->validate([
            'otp_code' => 'required'
        ]);
        $setting = TwoFactorSetting::where('school_id', Auth::user()->school_id)->first();
        $verify = UserOtpCode::query();
        $verify->where('user_id', auth()->user()->id)->where('otp_code', $request->otp_code); 
        if($setting->expired_time != 0){
            $verify->where('expired_time', '>=', Carbon::now());
        }               
        $verify = $verify->first();
        if ($verify) {
            Session::put('user_2fa', auth()->user()->id);
            return redirect('/after-login');
        }

        Toastr::error('You entered wrong code', 'Failed');
        return redirect()->back()->with('error', 'You entered wrong code.')->withInput();
    }

    
  

    public function settingUpdate (Request $request)
    {
        try{
            $gs = generalSetting();
            $gs->two_factor = $request->two_factor ;
            $gs->save();
    
            session()->forget('generalSetting');
            session()->put('generalSetting', $gs);
    
            $setting = TwoFactorSetting::where('school_id', Auth::user()->school_id)->first();
            $setting->via_sms = $request->via_sms == "on" ? 1 : 0; 
            $setting->via_email = $request->via_email == "on" ? 1 : 0; 
            $setting->expired_time = $request->expired_time; 
            $setting->for_admin = $request->for_admin == "on" ? 1 : 0; 
            $setting->for_student = $request->for_student == "on" ? 2 : 0; 
            $setting->for_parent = $request->for_parent == "on" ? 3 : 0; 
            $setting->for_teacher = $request->for_teacher == "on" ? 4 : 0; 
            $setting->for_staff = $request->for_staff == "on" ? 6 : 0;  
            $setting->update();
         
            Toastr::success('Update Successfully', 'Success');
            return redirect()->back();
        }
        catch(\Exception $e){
            Toastr::error('Operation Failed', 'Fai');
            return redirect()->back();
        }
    }

    public function resend_code(){
        auth()->user()->generateCode();
        Toastr::success('Resend Successfully', 'Success');
        return redirect()->back();
    }

    
}
