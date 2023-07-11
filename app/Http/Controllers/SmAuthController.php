<?php

namespace App\Http\Controllers;

use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSchool;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmClassRoom;
use App\SmClassTime;
use App\SmsTemplate;
use App\ApiBaseMethod;
use App\SmEmailSetting;
use Illuminate\Support\Str;
use App\SmBackgroundSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SmAuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function get_class_name(Request $request, $id)
    {
        $get_class_name = SmClass::select('class_name as name')->where('id', $id)->first();
        return $get_class_name;
    }

    public function get_section_name(Request $request, $id)
    {
        $get_section_name = SmSection::select('section_name as name')->where('id', $id)->first();
        return $get_section_name;
    }

    public function get_teacher_name(Request $request, $id)
    {
        $get_teacher_name = SmStaff::select('full_name as name')->where('id', $id)->first();
        return $get_teacher_name;
    }

    public function get_subject_name(Request $request, $id)
    {
        $get_subject_name = SmSubject::select('subject_name as name')->where('id', $id)->first();
        return $get_subject_name;
    }

    public function get_room_name(Request $request, $id)
    {
        $get_room_name = SmClassRoom::select('room_no as name')->where('id', $id)->first();
        return $get_room_name;
    }

    public function get_class_period_name(Request $request, $id)
    {
        $get_class_period_name = SmClassTime::select('period as name', 'start_time', 'end_time')->where('id', $id)->first();
        return $get_class_period_name;
    }

    public function getLoginAccess(Request $request)
    {
        if ($request->value == "Student") {
            $user = User::where('role_id', 2)->where('school_id', Auth::user()->school_id)->first();
        } elseif ($request->value == "Parents") {
            $user = User::where('role_id', 3)->where('school_id', Auth::user()->school_id)->first();
        } elseif ($request->value == "Super Admin") {
            $user = User::where('role_id', 1)->where('school_id', Auth::user()->school_id)->first();
        } elseif ($request->value == "Admin") {
            $user = User::where('role_id', 5)->where('school_id', Auth::user()->school_id)->first();
        } elseif ($request->value == "Teacher") {
            $user = User::where('role_id', 4)->where('school_id', Auth::user()->school_id)->first();
        } elseif ($request->value == "Accountant") {
            $user = User::where('role_id', 6)->where('school_id', Auth::user()->school_id)->first();
        } elseif ($request->value == "Receptionist") {
            $user = User::where('role_id', 7)->where('school_id', Auth::user()->school_id)->first();
        } elseif ($request->value == "Librarian") {
            $user = User::where('role_id', 8)->where('school_id', Auth::user()->school_id)->first();
        }
        return response()->json($user);
    }

    public function recoveryPassord()
    {
        try {
            $login_background = SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->first();

            if (empty($login_background)) {
                $css = "background: url(" . url('public/backEnd/img/login-bg.jpg') . ")  no-repeat center; background-size: cover; ";
            } else {
                if (!empty($login_background->image)) {
                    $css = "background: url('" . url($login_background->image) . "')  no-repeat center;  background-size: cover;";
                } else {
                    $css = "background:" . $login_background->color;
                }
            }
            return view('auth.recovery_password',compact('css'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function emailVerify(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);
        try {
            $emailCheck = User::select('*')->where('email', $request->email)->first();
            if ($emailCheck == "") {
                return redirect()->back()->with('message-danger', "Invalid Email, Please try again");
            } else {
                $admissionNumber = '';
                $student = SmStudent::where('user_id',$emailCheck->id)->first();
                if($student){
                    if($emailCheck->role_id == 2){
                        $admissionNumber = $student->admission_number;
                    }
                }

                $random= Str::random(32);
                // $data['id'] = Str::random(32);
                $user = User::where('email', $request->email)->first();
                $user->random_code = $random;
                $user->save();
                try{
                    $data['user_email']= $request->email;
                    $data['id'] = $emailCheck->id;
                    $data['random'] = $user->random_code;
                    $data['role_id'] = $user->role_id;
                    $data['admission_number'] = $admissionNumber;
                    @send_mail($user->email, $user->full_name, "password_reset", $data);
               }catch(\Exception $e){
                   Log::info($e->getMessage());
               }
               Toastr::success('Please check your email', 'Success');
                return redirect()->back()->with('message-success', 'Success ! Please check your email');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function resetEmailConfirtmation($email, $code)
    {
        try {
            $user = User::where('email', $email)->where('random_code', $code)->first();
            if ($user != "") {
                $email = $user->email;
                return view('auth.new_password', compact('email'));
            } else {
                Toastr::error('You have clicked on a invalid link', 'Failed');
                return redirect('recovery/passord');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function storeNewPassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|same:confirm_password',
            'confirm_password' => 'required'
        ]);
        try {
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->new_password);
            $user->random_code = '';
            $result = $user->save();
            if ($result) {
                Toastr::success('Password has beed reset successfully', 'Success');
                return redirect('login')->with('message-success', 'Password has beed reset successfully');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function mobileLogin(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => "required",
            'password' => "required"
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
            $user = User::where('email', $request->email)->first();
            if ($user != "") {
                if (Hash::check($request->password, $user->password)) {
                    $data = [];
                    $data['user'] = $user->toArray();
                    $role_id = $user->role_id;
                    if ($role_id == 2) {
                        $data['userDetails'] = DB::table('sm_students')->select('sm_students.*', 'sm_parents.*', 'sm_classes.*', 'sm_sections.*')
                            ->join('sm_parents', 'sm_parents.id', '=', 'sm_students.parent_id')
                            ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                            ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
                            ->where('sm_students.user_id', $user->id)
                            ->first();
                        $data['religion'] = DB::table('sm_students')->select('sm_base_setups.base_setup_name as name')
                            ->join('sm_base_setups', 'sm_base_setups.id', '=', 'sm_students.religion_id')
                            ->where('sm_students.user_id', $user->id)
                            ->first();
                        $data['blood_group'] = DB::table('sm_students')->select('sm_base_setups.base_setup_name as name')
                            ->join('sm_base_setups', 'sm_base_setups.id', '=', 'sm_students.bloodgroup_id')
                            ->where('sm_students.user_id', $user->id)
                            ->first();
                        $data['transport'] = DB::table('sm_students')
                            ->select('sm_vehicles.vehicle_no', 'sm_vehicles.vehicle_model', 'sm_staffs.full_name as driver_name', 'sm_vehicles.note')
                            ->join('sm_vehicles', 'sm_vehicles.id', '=', 'sm_students.vechile_id')
                            ->join('sm_staffs', 'sm_staffs.id', '=', 'sm_vehicles.driver_id')
                            ->where('sm_students.user_id', $user->id)
                            ->first();
                        $data['system_settings'] = DB::table('sm_general_settings')->where('school_id', Auth::user()->school_id)->get();
                        $data['TTL_RTL_status'] = '1=RTL,2=TTL';
                    } else if ($role_id == 3) {
                        $data['userDetails'] = SmParent::where('user_id', $user->id)->first();
                    } else {
                        $data['userDetails'] = SmStaff::where('user_id', $user->id)->first();
                    }
                    return ApiBaseMethod::sendResponse($data, 'Login successful.');
                } else {
                    return ApiBaseMethod::sendError('These credentials do not match our records.');
                }
            } else {
                return ApiBaseMethod::sendError('These credentials do not match our records.');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function setToken(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->notificationToken = $request->token;
            $user->save();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = '';
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function childInfo(Request $request, $user_id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $user = SmStudent::where('user_id', $user_id)->first();
                $data = [];
                $data['user'] = $user->toArray();
                $data['userDetails'] = DB::table('sm_students')->select('sm_students.*', 'sm_parents.*', 'sm_classes.*', 'sm_sections.*')
                    ->join('sm_parents', 'sm_parents.id', '=', 'sm_students.parent_id')
                    ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                    ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
                    ->where('sm_students.id', $user->id)
                    ->first();
                $data['religion'] = DB::table('sm_students')->select('sm_base_setups.base_setup_name as name')
                    ->join('sm_base_setups', 'sm_base_setups.id', '=', 'sm_students.religion_id')
                    ->where('sm_students.id', $user->id)
                    ->first();

                $data['blood_group'] = DB::table('sm_students')->select('sm_base_setups.base_setup_name as name')
                    ->join('sm_base_setups', 'sm_base_setups.id', '=', 'sm_students.bloodgroup_id')
                    ->where('sm_students.id', $user->id)
                    ->first();
                $data['transport'] = DB::table('sm_students')
                    ->select('sm_vehicles.vehicle_no', 'sm_vehicles.vehicle_model', 'sm_staffs.full_name as driver_name', 'sm_vehicles.note')
                    ->join('sm_vehicles', 'sm_vehicles.id', '=', 'sm_students.vechile_id')
                    ->join('sm_staffs', 'sm_staffs.id', '=', 'sm_students.vechile_id')
                    ->where('sm_students.id', $user->id)
                    ->first();
                    return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
