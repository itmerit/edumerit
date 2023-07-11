<?php

namespace App\Http\Controllers\Admin\SystemSettings;

use App\SmSmsGateway;
use Illuminate\Http\Request;
use App\Models\CustomSmsSetting;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomSmsSettingController extends Controller
{
    public function store(Request $request){
       
        Session::put('Custom_sms', 'active');

        $validator = Validator::make($request->all(), [
            'gateway_name' => 'required',
            'gateway_url' => 'required|url',
            'send_to_parameter_name' => 'required',
            'messege_to_parameter_name' => 'required',
            'request_method' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach($errors->all() as $error){
                Toastr::error(str_replace('custom f.', '', $error), 'Failed');
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try{
            $gateway = new SmSmsGateway();
            $gateway->gateway_name = $request->gateway_name;
            $gateway->gateway_type = "custom";
            $gateway->school_id = Auth::user()->school_id;
            $result = $gateway->save();
            if($result){
                $customSmsSetting = new CustomSmsSetting();
                $customSmsSetting->gateway_id = $gateway->id;
                $customSmsSetting->gateway_name = $request->gateway_name;
                $customSmsSetting->set_auth = $request->set_auth;
                $customSmsSetting->gateway_url = $request->gateway_url;
                $customSmsSetting->send_to_parameter_name = $request->send_to_parameter_name;
                $customSmsSetting->messege_to_parameter_name = $request->messege_to_parameter_name;
                $customSmsSetting->request_method = $request->request_method;
                $customSmsSetting->param_key_1 = $request->param_key_1;
                $customSmsSetting->param_value_1 = $request->param_value_1;
                $customSmsSetting->param_key_2 = $request->param_key_2;
                $customSmsSetting->param_value_2 = $request->param_value_2;
                $customSmsSetting->param_key_3 = $request->param_key_3;
                $customSmsSetting->param_value_3 = $request->param_value_3;
                $customSmsSetting->param_key_4 = $request->param_key_4;
                $customSmsSetting->param_value_4 = $request->param_value_4;
                $customSmsSetting->param_key_5 = $request->param_key_5;
                $customSmsSetting->param_value_5 = $request->param_value_5;
                $customSmsSetting->param_key_6 = $request->param_key_6;
                $customSmsSetting->param_value_6 = $request->param_value_6;
                $customSmsSetting->param_key_7 = $request->param_key_7;
                $customSmsSetting->param_value_7 = $request->param_value_7;
                $customSmsSetting->param_key_8 = $request->param_key_8;
                $customSmsSetting->param_value_8 = $request->param_value_8;
                $customSmsSetting->school_id = Auth::user()->school_id;
                $customSmsSetting->save();
            }
          
            Toastr::success('Operation Successfull', 'Success');
            return redirect()->back();
        }
        catch (\Exception $e) {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }
    }  
    
    public function edit($id){

        Session::put('Custom_sms', 'active');
        $editData = CustomSmsSetting::where('gateway_id',$id)->first();
        $sms_services['Twilio'] = SmSmsGateway::where('gateway_name','Twilio')->where('school_id',Auth::user()->school_id)->firstOrCreate();
        $sms_services['Msg91'] = SmSmsGateway::where('gateway_name','Msg91')->where('school_id',Auth::user()->school_id)->firstOrCreate();
        $sms_services['TextLocal'] = SmSmsGateway::where('gateway_name','TextLocal')->where('school_id',Auth::user()->school_id)->firstOrCreate();
        $sms_services['AfricaTalking'] = SmSmsGateway::where('gateway_name','AfricaTalking')->where('school_id',Auth::user()->school_id)->firstOrCreate();
        $sms_services['Mobile SMS'] = SmSmsGateway::where('gateway_name','Mobile SMS')->where('school_id',Auth::user()->school_id)->firstOrCreate();
        if(moduleStatusCheck('HimalayaSms')){
            $sms_services['HimalayaSms'] = SmSmsGateway::where('gateway_name','HimalayaSms')->where('school_id',Auth::user()->school_id)->first();
            $all_sms_services= SmSmsGateway::where('school_id',Auth::user()->school_id)->get();
        }
        elseif( ! moduleStatusCheck('HimalayaSms')){
            $all_sms_services= SmSmsGateway::where('gateway_name', '!=','HimalayaSms')->where('school_id',Auth::user()->school_id)->get();
        }
        $active_sms_service = SmSmsGateway::where('school_id',Auth::user()->school_id)->where('active_status', 1)->first();


        return view('backEnd.systemSettings.smsSettings', compact('sms_services', 'active_sms_service','all_sms_services','editData'));
    }


    public function update(Request $request){
      
        Session::put('Custom_sms', 'active');
        $request->validate([
            'gateway_name' => 'required',
            'gateway_url' => 'required|url',
            'send_to_parameter_name' => 'required',
            'messege_to_parameter_name' => 'required',
            'request_method' => 'required',
        ]);
        try{
            $result = null;
            $customSmsSetting = CustomSmsSetting::find($request->id);
           
            if($customSmsSetting){
                $gateway =  SmSmsGateway::find($customSmsSetting->gateway_id);
                if( $gateway){
                    $gateway->gateway_name = $request->gateway_name;
                    $result = $gateway->save();
                }
            }

            if($result){
                $customSmsSetting->gateway_id = $gateway->id;
                $customSmsSetting->gateway_name = $request->gateway_name;
                $customSmsSetting->gateway_url = $request->gateway_url;
                $customSmsSetting->set_auth = $request->set_auth;
                $customSmsSetting->send_to_parameter_name = $request->send_to_parameter_name;
                $customSmsSetting->messege_to_parameter_name = $request->messege_to_parameter_name;
                $customSmsSetting->request_method = $request->request_method;
                $customSmsSetting->param_key_1 = $request->param_key_1;
                $customSmsSetting->param_value_1 = $request->param_value_1;
                $customSmsSetting->param_key_2 = $request->param_key_2;
                $customSmsSetting->param_value_2 = $request->param_value_2;
                $customSmsSetting->param_key_3 = $request->param_key_3;
                $customSmsSetting->param_value_3 = $request->param_value_3;
                $customSmsSetting->param_key_4 = $request->param_key_4;
                $customSmsSetting->param_value_4 = $request->param_value_4;
                $customSmsSetting->param_key_5 = $request->param_key_5;
                $customSmsSetting->param_value_5 = $request->param_value_5;
                $customSmsSetting->param_key_6 = $request->param_key_6;
                $customSmsSetting->param_value_6 = $request->param_value_6;
                $customSmsSetting->param_key_7 = $request->param_key_7;
                $customSmsSetting->param_value_7 = $request->param_value_7;
                $customSmsSetting->param_key_8 = $request->param_key_8;
                $customSmsSetting->param_value_8 = $request->param_value_8;
                $customSmsSetting->save();
            }
          
            Toastr::success('Operation Successfull', 'Success');
            return redirect()->route('sms-settings');
        }
            catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }  


    public function testSms(Request $request){
        
        Session::put('select_sms_service', 'active');
        $request->validate([
            'reciver_no' => 'required',
        ]);

        @send_sms($request->reciver_no, 'test_sms', $compact= null);
        
        Toastr::success('Operation Successfull', 'Success');
        return redirect()->back();
    }

    public function delete(Request $request){
        $id = $request->id;
        Session::put('Custom_sms', 'active');
        if($id){
           $gateway= SmSmsGateway::find($id);
            if($gateway){
                $gateway->delete();
            }
            Toastr::success('Operation Successfull', 'Success');
            return redirect()->back();
            }
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
        
}
