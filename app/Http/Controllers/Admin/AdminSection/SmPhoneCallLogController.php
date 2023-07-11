<?php

namespace App\Http\Controllers\Admin\AdminSection;

use DataTables;
use App\ApiBaseMethod;
use App\SmPhoneCallLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\AdminSection\SmPhoneCallRequest;

class SmPhoneCallLogController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try{
            return view('backEnd.admin.phone_call');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(SmPhoneCallRequest $request)
    {
        try{
            $phone_call_log = new SmPhoneCallLog();
            $phone_call_log->name = $request->name;
            $phone_call_log->phone = $request->phone;
            $phone_call_log->date = date('Y-m-d', strtotime($request->date));
            $phone_call_log->description = $request->description;
            $phone_call_log->next_follow_up_date = date('Y-m-d', strtotime($request->follow_up_date));
            $phone_call_log->call_duration = $request->call_duration;
            $phone_call_log->call_type = $request->call_type;
            $phone_call_log->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $phone_call_log->un_academic_id = getAcademicId();
            }else{
                $phone_call_log->academic_id = getAcademicId();
            }
            $phone_call_log->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function show(Request $request, $id)
    {
        try{
            $phone_call_logs = SmPhoneCallLog::get();
            $phone_call_log = SmPhoneCallLog::find($id);
            return view('backEnd.admin.phone_call', compact('phone_call_logs', 'phone_call_log'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(SmPhoneCallRequest $request, $id)
    {
        try{
            $phone_call_log = SmPhoneCallLog::find($request->id);
            $phone_call_log->name = $request->name;
            $phone_call_log->phone = $request->phone;
            $phone_call_log->date = date('Y-m-d', strtotime($request->date));
            $phone_call_log->description = $request->description;
            $phone_call_log->next_follow_up_date = date('Y-m-d', strtotime($request->follow_up_date));
            $phone_call_log->call_duration = $request->call_duration;
            $phone_call_log->call_type = $request->call_type;
            if(moduleStatusCheck('University')){
                $phone_call_log->un_academic_id = getAcademicId();
            }
            $phone_call_log->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('phone-call');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try{
            $phone_call_log = SmPhoneCallLog::find($request->id);
            $result = $phone_call_log->delete();
             
            Toastr::success('Operation successful', 'Success');
            return redirect('phone-call');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function phoneCallDatatable()
    {
        try{
            $phone_call_logs = SmPhoneCallLog::query();
            return DataTables::of($phone_call_logs)
                    ->addIndexColumn()
                    ->addColumn('query_date', function($row){
                        return dateConvert(@$row->date);
                    })
                    ->addColumn('next_follow_up_date', function($row){
                        return dateConvert(@$row->next_follow_up_date);
                    })
                    ->addColumn('call_type', function($row){
                        return __('admin.' . ($row->call_type == 'I' ? 'incoming' : 'outgoing'));
                    })
                    ->addColumn('action', function ($row){
                        $btn = '<div class="dropdown CRM_dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                        
                                <div class="dropdown-menu dropdown-menu-right">'.
                                (userPermission('phone-call_edit') === true ? '<a class="dropdown-item" href="' . route('phone-call_edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                                (userPermission('phone-call_delete') === true ? (Config::get('app.app_sync') ? '<span data-toggle="tooltip" title="Disabled For Demo"><a class="dropdown-item" href="#" >' . app('translator')->get('common.disable') . '</a></span>' :
                                '<a onclick="deleteQueryModal(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteCallLogModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .
                            '</div>
                            </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'date'])
                    ->make(true);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}