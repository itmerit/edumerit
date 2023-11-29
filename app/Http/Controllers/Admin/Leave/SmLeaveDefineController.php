<?php

namespace App\Http\Controllers\Admin\Leave;
use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmStudent;
use App\SmLeaveType;
use App\SmLeaveDefine;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;
use Modules\University\Entities\UnSemesterLabel;
use App\Http\Requests\Admin\Leave\SmLeaveDefineRequest;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;

class SmLeaveDefineController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
      //  DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	}

    public function index(Request $request)
    {
        try{
            $leave_types = SmLeaveType::where('active_status', 1)->get();
            $roles = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1)->where('id', '!=', 3)->where('id', '!=', 10)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $classes = SmClass::get(['id','class_name']);
            return view('backEnd.humanResource.leave_define', compact('leave_types', 'roles','classes'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(SmLeaveDefineRequest $request)
    {
        try{
            $previous = null;
            if(! is_null($request->addition) ){
                $previous =  SmLeaveDefine::where('role_id',$request->member_type) 
                                            ->orWhere('user_id',$request->staff)
                                            ->orWhere('user_id',$request->student)
                                            ->where('type_id',$request->leave_type)
                                            ->latest()->first();
            }

            if( is_numeric($request->student)  || is_numeric($request->staff) ){
                if(is_null($previous)){
                    $leave_define = new SmLeaveDefine();
                    $leave_define->role_id = $request->member_type;
                    $leave_define->type_id = $request->leave_type;
                    $leave_define->days = $request->days;
                    $leave_define->school_id = Auth::user()->school_id;
                    if(moduleStatusCheck('University')){
                        $leave_define->un_academic_id = getAcademicId();
                    }else{
                        $leave_define->academic_id = getAcademicId();
                    }
                    $leave_define->academic_id = getAcademicId();
                    if(is_numeric($request->student)){
                        $leave_define->user_id = $request->student;
                    }
                    elseif(is_numeric($request->staff)){
                        $leave_define->user_id = $request->staff;
                    }
                    $results = $leave_define->save();
                }else{
                    $previous->days = ($previous->days + $request->days) ;
                    $results = $previous->save();
                }
            }else{
                if(moduleStatusCheck('University')){
                    $student_records = StudentRecord::where('un_semester_label_id',$request->un_semester_label_id)
                                    ->distinct('student_id')->with('student')->get();

                    if( count($student_records) > 0)  {
                        foreach($student_records as $record){
                            $leave_define = new SmLeaveDefine();
                            $leave_define->role_id = $record->student->role_id;
                            $leave_define->type_id = $request->leave_type;
                            $leave_define->days = $request->days;
                            $leave_define->school_id = Auth::user()->school_id;
                            $leave_define->user_id = $record->student->user_id;
                            $leave_define->un_semester_label_id = $request->un_semester_label_id;
                            $leave_define->un_academic_id = getAcademicId();
                            $leave_define->save();
                        }
                    } 

                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }
                $allUsers = User::where('school_id',Auth::user()->school_id)
                                        ->where('role_id',$request->member_type)
                                        ->where('selected_session', getAcademicId())
                                        ->where('active_status',1)
                                        ->get(['id','role_id']);

                if( count($allUsers) > 0)  {
                    foreach($allUsers as $user){
                        $leave_define = new SmLeaveDefine();
                        $leave_define->role_id = $user->role_id;
                        $leave_define->type_id = $request->leave_type;
                        $leave_define->days = $request->days;
                        $leave_define->school_id = Auth::user()->school_id;
                        if(moduleStatusCheck('University')){
                            $leave_define->un_academic_id = getAcademicId();
                        }else{
                            $leave_define->academic_id = getAcademicId();
                        }
                        $leave_define->user_id = $user->id;
                        $leave_define->save();
                    }
                }                     
            }

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
            $data = [];
            $leave_types = SmLeaveType::where('active_status', 1)->get();
            $roles = InfixRole::where('active_status', 1)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $leave_defines = SmLeaveDefine::get();
            $leave_define = SmLeaveDefine::find($id);

            $user = User::find($leave_define->user_id);
            $student = null;
            $staff = null;
            if($leave_define->role_id == 2){
                $smStudent = SmStudent::where('user_id',$user->id)->first();
                $student = StudentRecord::where('student_id', $smStudent->id)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', auth()->user()->school_id)
                    ->first();
            } else{
                $staff = SmStaff::where('user_id',$user->id)->first();
            }




            $classes = SmClass::get(['id','class_name']);
            $data['leave_types'] = $leave_types; 
            $data['leave_types'] = $leave_types; 
            $data['leave_defines'] = $leave_defines; 
            $data['leave_define'] = $leave_define; 
            $data['classes'] = $classes; 
            $data['student'] = $student; 
            $data['staff'] = $staff;
            if (moduleStatusCheck('University')) {
                if(isset($leave_define->un_semester_label_id)){
                    $data['editdata'] = UnSemesterLabel::find($leave_define->un_semester_label_id);
                    $interface = App::make(UnCommonRepositoryInterface::class);
                    $data += $interface->getCommonData($data['editdata']);
                }
            }
            return view('backEnd.humanResource.leave_define', $data);
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(SmLeaveDefineRequest $request, $id)
    {
        try{
            if (checkAdmin()) {
                $leave_define = SmLeaveDefine::find($request->id);
            }else{
                $leave_define = SmLeaveDefine::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $leave_define->type_id = $request->leave_type;
            $leave_define->days = $request->days;
            if(moduleStatusCheck('University')){
                $leave_define->un_academic_id = getAcademicId();
            }
            $leave_define->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('leave-define');
        }catch (\Exception $e) {

           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => "required"        
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try{
            $id = $request->id;
            $tables = \App\tableList::getTableList('leave_define_id', $id);
            try {
                if ($tables==null) {
                    if (checkAdmin()) {
                        SmLeaveDefine::destroy($id);
                    }else{
                        SmLeaveDefine::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }

                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function updateLeave(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => "required" ,
            'days'=> "required|numeric"      
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try{
            if (checkAdmin()) {
                $leave_define = SmLeaveDefine::find($request->id);
            }else{
                $leave_define = SmLeaveDefine::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $leave_define->days = $request->days;
            $leave_define->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('leave-define');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}