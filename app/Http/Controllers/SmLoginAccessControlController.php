<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmStudent;
use App\YearCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\SmSection;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\RolePermission\Entities\InfixRole;

class SmLoginAccessControlController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }


    public function loginAccessControl()
    {

        try {
            $roles = InfixRole::where('id', '!=', 1)->where('id', '!=', 3)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $classes = SmClass::get();

            return view('backEnd.systemSettings.login_access_control', compact('roles', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function searchUser(Request $request)
    {

        if ($request->role == "") {
            $request->validate([
                'role' => 'required'
            ]);
        }
        
        // elseif ($request->role == "2") {
        //     $request->validate([
        //         'role' => 'required',
        //         'class' => 'required',
        //     ]);
        // }

        

        try {
            $role = $request->role;
            $roles = InfixRole::where('id', '!=', 1)->where('id', '!=', 3)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $classes = SmClass::get();
            $students = SmStudent::query();
            $class = SmClass::find($request->class);
            $section = SmSection::find($request->section);
            $records = StudentRecord::query();
            if ($request->role == "2") {
                if (moduleStatusCheck('University')) {
                    $records = universityFilter($records, $request)->where('is_promote', 0);
                    $student_ids = $records->get('student_id')->toArray();
                    $students->whereIn('id', $student_ids);
                }else{
                    
                    $students->with(['parents', 'user','parents.parent_user', 'studentRecords' => function($q) use($request){
                        return $q->where('class_id', $request->class)->when($request->section, function($q) use($request){
                            $q->where('section_id', $request->section);
                        })->where('school_id', auth()->user()->school_id);
                    }])->whereHas('studentRecords', function($q) use($request){
                        return $q->where('class_id', $request->class)->when($request->section, function($q) use($request){
                            $q->where('section_id', $request->section);
                        })->where('school_id', auth()->user()->school_id);
                    });
                }

                $students->where('active_status', 1)
                ->where('school_id', auth()->user()->school_id);
                
                $students = $students->get();
               

                return view('backEnd.systemSettings.login_access_control', compact('students', 'role', 'roles', 'classes', 'class', 'section'));
            } elseif ($request->role == "3") {
                $parents = SmParent::with('parent_user')->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                return view('backEnd.systemSettings.login_access_control', compact('parents', 'role', 'roles', 'classes'));
            } else {
                $staffs = SmStaff::with('staff_user','roles')->where(function($q) use ($request) {
                    $q->where('role_id', $request->role)->orWhere('previous_role_id', $request->role);
                })->get();
                return view('backEnd.systemSettings.login_access_control', compact('staffs', 'role', 'roles', 'classes'));
            }
            return view('backEnd.systemSettings.login_access_control', compact('roles', 'classes'));
        } catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function loginAccessPermission(Request $request)
    {

        try {
            if ($request->status == 'on') {
                $status = 1;
            } else {
                $status = 0;
            }
            $user = User::find($request->id);
            $user->access_status = $status;
            $user->save();

            return response()->json(['status' => $request->status, 'users' => $user->access_status]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function loginPasswordDefault(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->password  = Hash::make('123456');
            $r = $user->save();
            if ($r) {
                $data['op'] = TRUE;
                $data['msg'] = "Success";
            } else {
                $data['op'] = FALSE;
                $data['msg'] = "Failed";
            }
            Log::info($user);
            return response()->json($data);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }
}