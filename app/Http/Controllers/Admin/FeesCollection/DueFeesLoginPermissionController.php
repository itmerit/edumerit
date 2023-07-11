<?php

namespace App\Http\Controllers\Admin\FeesCollection;

use App\User;
use App\SmClass;
use App\SmStudent;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Models\DueFeesLoginPrevent;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\RolePermission\Entities\InfixRole;

class DueFeesLoginPermissionController extends Controller
{
    public function index(){
        try{
            $roles = InfixRole::whereIn('id', [2,3])->where('school_id', auth()->user()->school_id)->get();
            $classes = SmClass::where('school_id',auth()->user()->school_id)->where('academic_id',getAcademicId())->get();
            return view('backEnd.feesCollection.due_fees_login_permission', compact('roles', 'classes'));
        }
        catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function search(Request $request){
        try{
            $roles = InfixRole::whereIn('id', [2,3])->where('school_id', auth()->user()->school_id)->get();
            $classes = SmClass::where('school_id',auth()->user()->school_id)->where('academic_id',getAcademicId())->get();
            $records = StudentRecord::query();
            $records->where('is_promote', 0)->where('school_id',auth()->user()->school_id);
            $records->when(moduleStatusCheck('University') && $request->filled('un_academic_id'), function ($u_query) use ($request) {
                $u_query->where('un_academic_id', $request->un_academic_id);
                }, function ($query) use ($request) {
                    $query->when($request->academic_year, function ($query) use ($request) {
                    $query->where('academic_id', $request->academic_year);
                    });
            })
            ->when(moduleStatusCheck('University') && $request->filled('un_faculty_id'), function ($u_query) use ($request) {
                $u_query->where('un_faculty_id', $request->un_faculty_id);
            }, function ($query) use ($request) {
                $query->when($request->class_id, function ($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                });
            })
            
            ->when(moduleStatusCheck('University') && $request->filled('un_department_id'), function ($u_query) use ($request) {
                $u_query->where('un_department_id', $request->un_department_id);
            }, function ($query) use ($request) {
                $query->when($request->section_id, function ($query) use ($request) {
                    $query->where('section_id', $request->section_id);
                });
            })
            ->when(!$request->academic_year && moduleStatusCheck('University')==false, function ($query) use ($request) {
                $query->where('academic_id', getAcademicId());
            })
            
            ->when( moduleStatusCheck('University') && $request->filled('un_session_id'), function ($query) use ($request) {
                $query->where('un_session_id', $request->un_session_id);
            })
            
            ->when( moduleStatusCheck('University') && $request->filled('un_semester_label_id'), function ($query) use ($request) {
                $query->where('un_semester_label_id', $request->un_semester_label_id);
            });
            $student_records = $records->where('is_promote', 0)->whereHas('student')->get(['student_id'])->unique('student_id')->toArray();
            $all_students =  SmStudent::with('studentRecords', 'parents','studentRecords.class','studentRecords.section','parents.parent_user')->whereIn('id',$student_records)
                                    ->where('active_status', 1)
                                    ->with(array('parents' => function ($query) {
                                        $query->select('id', 'fathers_name','user_id');
                                    }))
                                    ->with(array('gender' => function ($query) {
                                        $query->select('id', 'base_setup_name');
                                    }))
                                    ->when( $request->filled('admission_no'), function ($query) use ($request) {
                                        $query->where('admission_no', $request->admission_no);
                                    })
                                    ->when($request->name, function ($query) use ($request) {
                                        $query->where('full_name', 'like', '%' . $request->name . '%');
                                    });
    
            $students = $all_students->get();
    
            return view('backEnd.feesCollection.due_fees_login_permission', compact('roles', 'classes','students'));
        }
        catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }


    }

    public function store(Request $request){
        try{
            $user_id = $request->id;
            $status = $request->status;
            if($user_id){
                $user = User::find($user_id);
                $checkExist = DueFeesLoginPrevent::where('user_id',$user->id)->delete();
                if($user &&  $status == "on"){
                    $new = new DueFeesLoginPrevent();
                    $new->user_id = $user->id;
                    $new->role_id = $user->role_id;
                    $new->school_id = $user->school_id;
                    $new->academic_id = getAcademicId();
                    $new->save();
                }
            }
            return response()->json(['status' => $request->status, 'users' => $request->id]);
        }
        catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
