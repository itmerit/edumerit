<?php

namespace App\Http\Controllers\Admin\SystemSettings;

use App\SmClass;
use App\SmStaff;
use App\SmStudent;
use App\SmSubject;
use App\SmClassSection;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmClassOptionalSubject;
use App\SmOptionalSubjectAssign;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Academics\AssignOptionalSubjectSearch;
use App\Http\Requests\Admin\GeneralSettings\SmOptionalSetupStoreRequest;

class SmOptionalSubjectAssignController extends Controller
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
    public function assignOptionalSubject(Request $request)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }
    public function index(Request $request)
    {

        $classes = SmClass::get();
        $sections = SmClassSection::get();
        $assign_subjects = SmAssignSubject::get();
        $subjects = SmSubject::get();
        $teachers = SmStaff::where('role_id', 4)->get();
        return view('backEnd.academics.assign_optional_subject', compact('classes', 'sections', 'assign_subjects', 'subjects', 'teachers'));
    }

    public function assignOptionalSubjectSearch(AssignOptionalSubjectSearch $request)
    {
        try {
            $students = StudentRecord::with('studentDetail','studentDetail.subjectAssign', 'studentDetail.subjectAssign.subject')
            ->whereHas('studentDetail', function($q){
                return $q->where('active_status', 1);
            })
                        ->where('class_id', $request->class_id)
                        ->where('section_id', $request->section_id)
                        ->where('academic_id', getAcademicId())
                        ->where('is_promote', 0)
                        ->where('school_id', Auth::user()->school_id)
                        ->get();

            $subject_id = $request->subject_id;
            $subject_info = SmSubject::where('id', '=', $request->subject_id)->first();
            $subjects = SmSubject::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $teachers = SmStaff::where('active_status', 1)->where(function($q)  {
	        $q->where('role_id', 4)->orWhere('previous_role_id', 4);})->where('school_id', Auth::user()->school_id)->get();

            $class_id = $request->class_id;
            $section_id = $request->section_id;
            $classes = SmClass::get();
            $class = SmClass::with('classSection')->where('id', $class_id)->first();
            $assignSubjects= SmAssignSubject::with('subject')->where('class_id', $class_id)->where('section_id', $section_id)->get();
            return view('backEnd.academics.assign_optional_subject', compact('classes', 'teachers', 'subjects', 'class_id', 'section_id', 'students', 'subject_id', 'subject_info', 'class', 'assignSubjects'));
        } catch (\Exception $e) {    
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function assignOptionalSubjectStore(Request $request)
    {
        try {
            $old = SmOptionalSubjectAssign::where('subject_id', '=', $request->subject_id)
                ->where('school_id', Auth::user()->school_id)
                ->where('academic_id', getAcademicId())
                ->first();
            if (!is_null($old)) {
                SmOptionalSubjectAssign::where('subject_id', '=', $request->subject_id)
                    ->where('school_id', Auth::user()->school_id)
                    ->where('academic_id', getAcademicId())
                    ->delete();
            }
            if ($request->student_id != "") {

                foreach ($request->student_id as $student) {

                    $student_info = StudentRecord::where('id', $student)->first();
                    $optional_subject = SmOptionalSubjectAssign::where('record_id', '=', $student)
                                        ->where('session_id', '=', $student_info->studentDetail->session_id)
                                        ->first();
 
                    if ($optional_subject != '') {
                        $optional_subject = SmOptionalSubjectAssign::find($optional_subject->id);
                        $optional_subject->subject_id = $request->subject_id;
                        $optional_subject->updated_by = Auth::user()->id;
                        $optional_subject->academic_id = getAcademicId();
                        $optional_subject->save();
                    } else {
                        $optional_subject = new SmOptionalSubjectAssign();
                        $optional_subject->student_id = $student_info->studentDetail->id;
                        $optional_subject->record_id = $student;
                        $optional_subject->subject_id = $request->subject_id;
                        $optional_subject->session_id = $student_info->session_id;
                        $optional_subject->created_by = Auth::user()->id;
                        $optional_subject->school_id = Auth::user()->school_id;
                        $optional_subject->academic_id = getAcademicId();

                        $optional_subject->save();
                    }
                }

            }
            Toastr::success('Operation successful', 'Success');
            return redirect('optional-subject');

        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect('optional-subject');
        }
    }

    public function optionalSetup(Request $request)
    {

        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class_optionals = SmClassOptionalSubject::join('sm_classes', 'sm_classes.id', '=', 'sm_class_optional_subject.class_id')
                ->select('sm_class_optional_subject.*', 'class_name')
                ->where('sm_class_optional_subject.school_id', Auth::user()->school_id)
                ->where('sm_class_optional_subject.academic_id', getAcademicId())
                ->orderby('sm_class_optional_subject.id', 'DESC')
                ->get();
            return view('backEnd.systemSettings.optional_subject_setup', compact('classes', 'class_optionals'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function optionalSetupStore(SmOptionalSetupStoreRequest $request)
    {

        try {
            foreach ($request->class as $value) {
                $optional_check = SmClassOptionalSubject::where('class_id', '=', $value)->first();
                if ($optional_check == '') {
                    $class_optional = new SmClassOptionalSubject();
                    $class_optional->class_id = $value;
                } else {
                    $class_optional = SmClassOptionalSubject::where('class_id', '=', $value)->first();
                }
                $class_optional->gpa_above = $request->gpa_above;
                $class_optional->school_id = Auth::user()->school_id;
                $class_optional->created_by = Auth::user()->id;
                $class_optional->updated_by = Auth::user()->id;
                $class_optional->academic_id = getAcademicId();
                $class_optional->save();
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('optional-subject-setup');
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function optionalSetupDelete($id)
    {

        try {
            $class_optional = SmClassOptionalSubject::findOrfail($id);
            $class_optional->delete();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function optionalSetupEdit($id)
    {

        try {
            $editData = SmClassOptionalSubject::findOrfail($id);
            $classes = SmClass::where('active_status', 1)->where('school_id', Auth::user()->school_id)->where('sm_classes.academic_id', getAcademicId())->get();
            //    return $classes;
            $class_optionals = SmClassOptionalSubject::join('sm_classes', 'sm_classes.id', '=', 'sm_class_optional_subject.class_id')
                ->select('sm_class_optional_subject.*', 'class_name')
                ->where('sm_class_optional_subject.school_id', Auth::user()->school_id)->get();
            return view('backEnd.systemSettings.optional_subject_setup', compact('classes', 'class_optionals', 'editData'));
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
