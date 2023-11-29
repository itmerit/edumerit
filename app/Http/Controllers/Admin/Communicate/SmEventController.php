<?php

namespace App\Http\Controllers\Admin\Communicate;

use App\User;
use App\SmEvent;
use App\SmNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Communicate\EventRequest;

class SmEventController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try {
            $events = SmEvent::get();
            return view('backEnd.events.eventsList', compact('events'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(EventRequest $request)
    {
        try {
            $destination ='public/uploads/events/';
            $user = Auth()->user();       
            $login_id = $user ? $user->id : $request->login_id;           
            $events = new SmEvent();
            $events->event_title = $request->event_title;
            $events->for_whom = $request->for_whom;
            $events->event_des = $request->event_des;
            $events->event_location = $request->event_location;
            $events->from_date = date('Y-m-d', strtotime($request->from_date));
            $events->to_date = date('Y-m-d', strtotime($request->to_date));
            $events->created_by = $login_id;
            $events->uplad_image_file =fileUpload($request->upload_file_name,$destination);
            $events->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $events->un_academic_id = getAcademicId();
            }else{
                $events->academic_id = getAcademicId();
            }
            $events->save();
            if ($request->for_whom == 'All') {
                $users = User::where('school_id', Auth::user()->school_id)->where('active_status', 1)->get();
                foreach ($users as $value) {
                    $notification = new SmNotification;
                    $notification->user_id = $value->id;
                    $notification->role_id = $value->role_id;
                    $notification->date = date('Y-m-d');
                    $notification->message = $request->event_title;
                    $notification->school_id = Auth::user()->school_id;
                    if(moduleStatusCheck('University')){
                        $notification->un_academic_id = getAcademicId();
                    }else{
                        $notification->academic_id = getAcademicId();
                    }
                    $notification->save();
                }
            } elseif ($request->for_whom == 'Teacher') {
                $users = User::where('school_id', Auth::user()->school_id)->where('active_status', 1)->where('role_id', 4)->get();
                foreach ($users as $value) {
                    $notification = new SmNotification;
                    $notification->user_id = $value->id;
                    $notification->role_id = $value->role_id;
                    $notification->date = date('Y-m-d');
                    $notification->message = $request->event_title;
                    $notification->school_id = Auth::user()->school_id;
                    if(moduleStatusCheck('University')){
                        $notification->un_academic_id = getAcademicId();
                    }else{
                        $notification->academic_id = getAcademicId();
                    }
                    $notification->save();
                }
            } elseif ($request->for_whom == 'Student') {
                $users = User::where('school_id', Auth::user()->school_id)->where('active_status', 1)->where('role_id', 2)->get();
                foreach ($users as $value) {
                    $notification = new SmNotification;
                    $notification->user_id = $value->id;
                    $notification->role_id = $value->role_id;
                    $notification->date = date('Y-m-d');
                    $notification->message = $request->event_title;
                    $notification->school_id = Auth::user()->school_id;
                    if(moduleStatusCheck('University')){
                        $notification->un_academic_id = getAcademicId();
                    }else{
                        $notification->academic_id = getAcademicId();
                    }
                    $notification->save();
                }
            } elseif ($request->for_whom == 'Parents') {
                $users = User::where('school_id', Auth::user()->school_id)->where('active_status', 1)->where('role_id', 3)->get();
                foreach ($users as $value) {
                    $notification = new SmNotification;
                    $notification->user_id = $value->id;
                    $notification->role_id = $value->role_id;
                    $notification->date = date('Y-m-d');
                    $notification->message = $request->event_title;
                    $notification->school_id = Auth::user()->school_id;
                    if(moduleStatusCheck('University')){
                        $notification->un_academic_id = getAcademicId();
                    }else{
                        $notification->academic_id = getAcademicId();
                    }
                    $notification->save();
                }
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
             ;
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $editData = SmEvent::find($id);
            $events = SmEvent::get();
            return view('backEnd.events.eventsList', compact('editData', 'events'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(EventRequest $request, $id)
    {
        try {
            $destination ='public/uploads/events/';
            $user = Auth()->user();
            $login_id = $user ? $user->id : $request->login_id;   
            $events = SmEvent::find($id);
            $events->event_title = $request->event_title;
            $events->for_whom = $request->for_whom;
            $events->event_des = $request->event_des;
            $events->event_location = $request->event_location;
            $events->from_date = date('Y-m-d', strtotime($request->from_date));
            $events->to_date = date('Y-m-d', strtotime($request->to_date));
            $events->updated_by = $login_id;
            $events->uplad_image_file = fileUpdate($events->uplad_image_file,$request->upload_file_name,$destination);
            $events->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('event');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteEventView(Request $request, $id)
    {
        try {
            return view('backEnd.events.deleteEventView', compact('id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteEvent(Request $request, $id)
    {
        try {
            SmEvent::destroy($id);
            
            Toastr::success('Operation successful', 'Success');
            return redirect('event');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}