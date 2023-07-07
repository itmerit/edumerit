<?php

namespace Modules\StudentAbsentNotification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Modules\StudentAbsentNotification\Entities\AbsentNotificationTimeSetup;

class StudentAbsentNotificationController extends Controller
{
    public function index()
    {
        $setups=AbsentNotificationTimeSetup::where('school_id',Auth::user()->school_id)->get();
        return view('studentabsentnotification::index',compact('setups'));
    }

    public function create()
    {
        return view('studentabsentnotification::create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'start_time' => 'required',
            'active_status' => 'required',
        ],[
            'active_status.required' => 'The status field is required'
        ]);
        if ($validator->fails()) {
            return redirect()->route('notification_time_setup')
                    ->withErrors($validator)
                    ->withInput();
        }
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            $setup=new AbsentNotificationTimeSetup();
            $setup->time_from= date('H:i', strtotime($request->start_time));
            $setup->time_to=date('H:i', strtotime($request->end_time));
            $setup->active_status=$request->active_status;
            $setup->school_id=Auth::user()->school_id;
            $setup->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
         } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        return view('studentabsentnotification::show');
    }

    public function edit($id)
    {
        $editData=AbsentNotificationTimeSetup::where('school_id',Auth::user()->school_id)->where('id',$id)->first();
        $setups=AbsentNotificationTimeSetup::where('school_id',Auth::user()->school_id)->get();
        return view('studentabsentnotification::index',compact('setups','editData'));
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'start_time' => 'required',
            'active_status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $setup=AbsentNotificationTimeSetup::find($request->id);
            $setup->time_from= date('H:i', strtotime($request->start_time));
            $setup->time_to=date('H:i', strtotime($request->end_time));
            $setup->active_status=$request->active_status;
            $setup->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('studentabsentnotification');
         } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $setup =AbsentNotificationTimeSetup::find($id)->delete();
            Toastr::success('Operation successful', 'Success');
            return redirect('studentabsentnotification');
         } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
