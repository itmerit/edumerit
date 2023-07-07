<?php

namespace App\Http\Controllers\Admin\SystemSettings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\SmNotificationSetting;

class SmNotificationController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index()
    {
        try{
            $notificationSettings = SmNotificationSetting::where('school_id', auth()->user()->school_id)->get();
            return view('backEnd.notification_setting.notification_setting', compact('notificationSettings'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function notificationEventModal($id, $key)
    {
        try{
            $eventModal = SmNotificationSetting::find($id);
            $data = [];
            $data['id']=$id;
            $data['key']=$key;
            $data['shortcode'] = $eventModal->shortcode;
            $data['subject'] = $eventModal->subject[$key];
            $data['emailBody'] = $eventModal->template[$key]['Email'];
            $data['smsBody'] = $eventModal->template[$key]['SMS'];
            $data['appBody'] = $eventModal->template[$key]['App'];
            $data['webBody'] = $eventModal->template[$key]['Web'];
         
            return view('backEnd.notification_setting.notification_setting_modal', $data); 
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function notificationSettingsUpdate(Request $request)
    {
      
        try{
            $id = $request->id;
            $settings = SmNotificationSetting::where('school_id', auth()->user()->school_id)
            ->where('id', $id)->first();           
            
            if($request->type == 'destination') {
                $destinations = $settings->destination;
                if(array_key_exists($request->destination, $destinations)) {
                    $destinations[$request->destination]=(int)$request->status;
               }
               $settings->destination = $destinations;
               $settings->save();
            } 
            if($request->type == 'recipient-status') {
                $recipients = $settings->recipient;
                if(array_key_exists($request->recipient, $recipients)) {
                    $recipients[$request->recipient]=(int)$request->status;
               }
               $settings->recipient = $recipients;
               $settings->save();
            } 
            if($request->type == 'recipient') {
                $subjects = $settings->subject;
                if(array_key_exists($request->key, $subjects)) {
                    $subjects[$request->key]= $request->subject;
               }
               $templates = $settings->template;
               if(array_key_exists($request->key, $templates)) {
                    $templates[$request->key]['Email']= $request->email_body;
                    $templates[$request->key]['SMS']= $request->sms_body;
                    $templates[$request->key]['Web']= $request->web_body;
                    $templates[$request->key]['App']= $request->app_body;
                }
                $settings->subject = $subjects;
                $settings->template = $templates;
                $settings->save();
            }
            return response()->json();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}
