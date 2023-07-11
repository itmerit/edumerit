<?php

namespace App\Http\Controllers;

use App\User;
use App\SmEvent;
use App\SmStaff;
use App\SmParent;
use App\SmStudent;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmNotification;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmEventController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $events = SmEvent::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->orderby('id','DESC')->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($events, null);
            }
            return view('backEnd.events.eventsList', compact('events'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'event_title' => "required",
            'for_whom' => "required",
            'from_date' => "required",
            'to_date' => "required|after_or_equal:from_date",
            'event_des' => "required",
            'event_location' => 'required',
            'upload_file_name' => "required|mimes:jpg,jpeg,png,gif",
        ]);
      
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('upload_file_name');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $fileName = "";
            if ($request->file('upload_file_name') != "") {
                $file = $request->file('upload_file_name');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/events/', $fileName);
                $fileName = 'public/uploads/events/' . $fileName;
            }
            $user = Auth()->user();
            if ($user) {
                $login_id = $user->id;
            } else {
                $login_id = $request->login_id;
            }
            $events = new SmEvent();
            $events->event_title = $request->event_title;
            $events->for_whom = $request->for_whom;
            $events->event_des = $request->event_des;
            $events->event_location = $request->event_location;
            $events->from_date = date('Y-m-d', strtotime($request->from_date));
            $events->to_date = date('Y-m-d', strtotime($request->to_date));
            $events->created_by = $login_id;
            $events->uplad_image_file = $fileName;
            $events->school_id = Auth::user()->school_id;
            $events->academic_id = getAcademicId();
            $results = $events->save();
            if ($request->for_whom == 'All') {
                $users = User::where('school_id', Auth::user()->school_id)->where('active_status', 1)->get();
                foreach ($users as $value) {
                    $notification = new SmNotification;
                    $notification->user_id = $value->id;
                    $notification->role_id = $value->role_id;
                    $notification->date = date('Y-m-d');
                    $notification->message = $request->event_title;
                    $notification->school_id = Auth::user()->school_id;
                    $notification->academic_id = getAcademicId();
                    $notification->save();
                }
            } elseif ($request->for_whom == 'Teacher') {
                $users = User::where('school_id', Auth::user()->school_id)->where('active_status', 1)->whereHas('staff', function($subQ){
                    return $subQ->where(function($q)  {
                         $q->where('role_id', 4)->orWhere('previous_role_id', 4);
                    });
                })->get();
                foreach ($users as $value) {
                    $notification = new SmNotification;
                    $notification->user_id = $value->id;
                    $notification->role_id = $value->role_id;
                    $notification->date = date('Y-m-d');
                    $notification->message = $request->event_title;
                    $notification->school_id = Auth::user()->school_id;
                    $notification->academic_id = getAcademicId();
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
                    $notification->academic_id = getAcademicId();
                    $notification->save();
                }
            } elseif ($request->for_whom == 'Parents') {
                $users = User::where('school_id', Auth::user()->school_id)
                ->where('active_status', 1)
                ->whereHas('staff', function($subQ){
                    return $subQ->where(function($q)  {
                         $q->where('role_id', 3)->orWhere('previous_role_id', 3);
                    });
                })->get();
                foreach ($users as $value) {
                    $notification = new SmNotification;
                    $notification->user_id = $value->id;
                    $notification->role_id = $value->role_id;
                    $notification->date = date('Y-m-d');
                    $notification->message = $request->event_title;
                    $notification->school_id = Auth::user()->school_id;
                    $notification->academic_id = getAcademicId();
                    $notification->save();
                }
            }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($results) {
                    return ApiBaseMethod::sendResponse(null, 'New Event has been added successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($results) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {
        try {
             if (checkAdmin()) {
                $editData = SmEvent::find($id);
            }else{
                $editData = SmEvent::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $events = SmEvent::where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['editData'] = $editData->toArray();
                $data['events'] = $events->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.events.eventsList', compact('editData', 'events'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'event_title' => "required",
            'for_whom' => "required",
            'from_date' => "required",
            'to_date' => "required",
            'event_des' => "required",
            'event_location' => "required",
            'upload_file_name' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",

        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('upload_file_name');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }

            if (checkAdmin()) {
                $events = SmEvent::find($id);
            }else{
                $events = SmEvent::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }

            $fileName = "";
            if ($request->file('upload_file_name') != "") {
                $eventFile = SmEvent::find($id);
                if ($eventFile->uplad_image_file != "") {
                    unlink($eventFile->uplad_image_file);
                }
                $file = $request->file('upload_file_name');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/events/', $fileName);
                $fileName = 'public/uploads/events/' . $fileName;
                $events->uplad_image_file = $fileName;
            }

            $user = Auth()->user();

            if ($user) {
                $login_id = $user->id;
            } else {
                $login_id = $request->login_id;
            }

            $events->event_title = $request->event_title;
            $events->for_whom = $request->for_whom;
            $events->event_des = $request->event_des;
            $events->event_location = $request->event_location;
            $events->from_date = date('Y-m-d', strtotime($request->from_date));
            $events->to_date = date('Y-m-d', strtotime($request->to_date));
            $events->updated_by = $login_id;
            $results = $events->update();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($results) {
                    return ApiBaseMethod::sendResponse(null, 'Event has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($results) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('event');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteEventView(Request $request, $id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                return ApiBaseMethod::sendResponse($id, null);
            }
            return view('backEnd.events.deleteEventView', compact('id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteEvent(Request $request, $id)
    {

        try {
            if (checkAdmin()) {
                $events = SmEvent::destroy($id);
            }else{
                $events = SmEvent::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($events) {
                    return ApiBaseMethod::sendResponse(null, 'Event has been deleted successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($events) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('event');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}