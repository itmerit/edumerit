<?php

namespace App\Http\Controllers;

use App\InfixModuleManager;
use App\Role;
use App\SmAcademicYear;
use App\SmDateFormat;
use App\SmLanguage;
use App\SmsTemplate;
use App\SmUserLog;
use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmStudent;
use App\YearCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\SmSection;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;
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

    public function secretLogin(Request $request)
    {
        Cache::forget('sidebars' . auth()->user()->id);
        Session::flush();
        Auth::logout();
        $logged_in = Auth::loginUsingId($request->user_id);
        if ($logged_in) {
            Session::put('role_id', $logged_in->role_id);
            return redirect()->route('dashboard');
        }


    }

    private function loginSession($user_id)
    {
        Cache::forget('sidebars' . auth()->user()->id);
        userStatusChange($user_id, 0);
        Session::flush();
        Auth::logout();

        $logged_in = Auth::loginUsingId($user_id);
        if ($logged_in) {

            if (!Auth::user()->access_status) {
                $this->guard()->logout();
                Toastr::error('You are not allowed, Please contact with administrator.', 'Failed');
                return redirect()->route('login');
            }

            // System date format save in session
            $date_format_id = generalSetting()->date_format_id;
            $system_date_format = 'jS M, Y';
            if($date_format_id){
                $system_date_format = SmDateFormat::where('id', $date_format_id)->first(['format'])->format;
            }

            session()->put('system_date_format', $system_date_format);

            // System academic session id in session

            $all_modules = [];
            $modules = InfixModuleManager::select('name')->get();
            foreach ($modules as $module) {
                $all_modules[] = $module->name;
            }

            session()->put('all_module', $all_modules);

            //Session put text decoration
            $ttl_rtl = generalSetting()->ttl_rtl;
            session()->put('text_direction', $ttl_rtl);


            //Session put activeLanguage
            $systemLanguage = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            session()->put('systemLanguage', $systemLanguage);
            //session put academic years

            if(moduleStatusCheck('University')){
                $academic_years = Auth::check() ? UnAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get() : '';
            }else{
                $academic_years = Auth::check() ? SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get() : '';
            }
            session()->put('academic_years', $academic_years);
            //session put sessions and selected language



            $profile = SmStaff::where('user_id', Auth::id())->first();
            if ($profile) {
                session()->put('profile', $profile->staff_photo);
            }
            $session_id = $profile && $profile->academic_id ? $profile->academic_id : generalSetting()->session_id;


            if(moduleStatusCheck('University')){
                $session_id = generalSetting()->un_academic_id;
                if(!$session_id){
                    $session = UnAcademicYear::where('school_id', Auth::user()->school_id)->where('active_status', 1)->first();
                } else{
                    $session = UnAcademicYear::find($session_id);
                }

                session()->put('sessionId', $session->id);
                session()->put('session', $session);
            }
            else{
                if(!$session_id){
                    $session = SmAcademicYear::where('school_id', Auth::user()->school_id)->where('active_status', 1)->first();
                } else{
                    $session = SmAcademicYear::find($session_id);
                }
                session()->put('sessionId', $session->id);
                session()->put('session', $session);
            }

            if(!$session){
                $session = SmAcademicYear::where('school_id', Auth::user()->school_id)->first();
            }



            session()->put('sessionId', $session->id);
            session()->put('session', $session);
            session()->put('school_config', generalSetting());

            $dashboard_background = DB::table('sm_background_settings')->where([['is_default', 1], ['title', 'Dashboard Background']])->first();
            session()->put('dashboard_background', $dashboard_background);

            $email_template = SmsTemplate::where('school_id',Auth::user()->school_id)->first();
            session()->put('email_template', $email_template);

            session(['role_id' => Auth::user()->role_id]);
            $agent = new Agent();
            $user_log = new SmUserLog();
            $user_log->user_id = Auth::user()->id;
            $user_log->role_id = Auth::user()->role_id;
            $user_log->school_id = Auth::user()->school_id;
            $user_log->ip_address = \Request::ip();
            if(moduleStatusCheck('University')){
                $user_log->un_academic_id = getAcademicid();
            }else{
                $user_log->academic_id = getAcademicid() ?? 1;
            }
            $user_log->user_agent = $agent->browser() . ', ' . $agent->platform();
            $user_log->save();

            userStatusChange(auth()->user()->id, 1);

        }
    }
}
