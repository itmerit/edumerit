<?php

namespace App\Http\Controllers\api;

use App\Role;

use App\User;

use App\SmClass;

use App\SmSection;

use App\SmStudent;

use App\SmSubject;

use App\YearCheck;



use App\ApiBaseMethod;

use App\SmAcademicYear;


use App\SmAssignSubject;

use App\SmSubjectAttendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SubjectWiseAttendanceController extends Controller
{
    public function SelectSubject(Request $request){
       
        $input = $request->all();

        $validator = Validator::make($input, [

            'class' => 'required',

            'section' => 'required'

        ]);
        if ($validator->fails()) {

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }

            return redirect()->back()

                ->withErrors($validator)

                ->withInput();

        }
            $subject_all = SmAssignSubject::where('class_id', '=', $request->class)
            ->where('section_id', $request->section)
            ->distinct('subject_id')
            ->get();

        $students = [];
        foreach ($subject_all as $allSubject) {
            $students[] = SmSubject::where('id',$allSubject->subject_id)->first(['subject_name','id','subject_type']);
        }
        return ApiBaseMethod::sendResponse($students, null);
    }
   

    public function studentSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
            'subject' => 'required',
            'attendance_date' => 'required'
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
            $date = $request->attendance_date;
            $classes = SmClass::where('active_status', 1)->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->get();

            $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('active_status', 1)->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->get();

            if ($students->isEmpty()) {
                return ApiBaseMethod::sendError('No Result Found',null);
            }

            $already_assigned_students = [];
            $new_students = [];
            $attendance_type = "";
            foreach ($students as $student) {
                $attendance = SmSubjectAttendance::where('student_id', $student->id)->where('subject_id', $request->subject)->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->first();

                if ($attendance != "") {
                    $already_assigned_students[] = $attendance;
                    $attendance_type =  $attendance->attendance_type;
                } else {
                    $new_students[] =  $student;
                }
            }

            $class_id = $request->class;
            $class_info = SmClass::find($request->class);
            $section_info = SmSection::find($request->section);

            $search_info['class_name'] = $class_info->class_name;
            $search_info['section_name'] = $section_info->section_name;
            $search_info['date'] = $request->attendance_date;


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['date'] = $date;
                $data['class_id'] = $class_id;
                $data['already_assigned_students'] = $already_assigned_students;
                $data['new_students'] = $new_students;
                $data['attendance_type'] = $attendance_type;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
           return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function studentAttendanceStore(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
            'subject' => 'required',
            'date' => 'required'
        ]);



        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // return $request;
        try {
            foreach ($request->id as $student) {
                $attendance = SmSubjectAttendance::where('student_id', $student)
                ->where('subject_id', $request->subject)
                ->where('attendance_date', date('Y-m-d', strtotime($request->date)))
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->first();

                if ($attendance != "") {
                    $attendance->delete();
                }


                $attendance = new SmSubjectAttendance();
                $attendance->student_id = $student;
                $attendance->subject_id = $request->subject;
                if (isset($request->mark_holiday)) {
                    $attendance->attendance_type = "H";
                } else {
                    $attendance->attendance_type = $request->attendance[$student];
                    $attendance->notes = $request->note[$student];
                }
                $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
                $attendance->save();

            }

                return ApiBaseMethod::sendResponse(null, 'Student attendance been submitted successfully');
        } catch (\Exception $e) {
           return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }

    public function studentAttendanceCheck(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'date' => "required",
            'class' => "required",
            'subject' => 'required',
            'section' => "required"
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
 
        }
        $student_ids = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->select('id')->get();
        $students = SmStudent::with('class','section')->where('class_id', $request->class)->where('section_id', $request->section)->get();
        $studentAttendance=SmSubjectAttendance::whereIn('student_id', $student_ids)->where('subject_id', $request->subject)->where('attendance_date', date('Y-m-d', strtotime($request->date)))->orderby('student_id','ASC')->get();

                $student_attendance=[];
                $no_attendance=[];
                 if(count($studentAttendance)==0){
         
			            foreach($students as $student){

			                $d['id']=$student->id;
			                $d['student_id']=$student->id;
			                $d['student_photo']=@$student->student_photo;
			                $d['full_name']=$student->full_name;
			                $d['roll_no']=  $student->roll_no;
			                $d['class_name']=$student->class->class_name;
			                $d['section_name']=  $student->section->section_name;    
			                $d['attendance_type']=null;
			                $d['user_id']=$student->user_id;
			    
			                $no_attendance[]=$d;
			            }
       				 }else{
			        foreach ($studentAttendance as $attendance){

			            $d['id']=$attendance->id;
			            $d['student_id']=$attendance->student_id;
			            $d['student_photo']=$attendance->student->student_photo;
			            $d['full_name']=$attendance->student->full_name;
			            $d['roll_no']=  $attendance->student->roll_no;
			            $d['class_name']=$attendance->student->class->class_name;
			            $d['section_name']=  $attendance->student->section->section_name;    
			            $d['attendance_type']=$attendance->attendance_type;
			            $d['user_id']=$attendance->student->user_id;
			            
			            $student_attendance[]=$d;
			        }
                }
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
               if (count($studentAttendance)>0) {
                    return ApiBaseMethod::sendResponse($student_attendance,null);
                } else {
                    return ApiBaseMethod::sendResponse($no_attendance,'Student attendance not done yet');
                }
         }       


        // if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //     return ApiBaseMethod::sendResponse(null, 'Student attendance been submitted successfully');
        // }
    }
    public function studentAttendanceStoreFirst(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'date' => "required",
            'class' => "required",
            'subject' => 'required',
            'section' => "required"
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->select('id')->get();
        $attendance = SmSubjectAttendance::where('student_id', $request->id)->where('subject_id', $request->subject)->where('attendance_date', date('Y-m-d', strtotime($request->date)))->first();
        if (empty($attendance)) {
            foreach ($students as $student) {
                $attendance = SmSubjectAttendance::where('student_id', $student->id)->where('subject_id', $request->subject)->where('attendance_date', date('Y-m-d', strtotime($request->date)))->first();
                if ($attendance != "") {
                    $attendance->delete();
                } else {
                    $attendance = new SmSubjectAttendance();
                    $attendance->student_id = $student->id;
                    $attendance->subject_id = $request->subject;
                    $attendance->attendance_type = "P";
                    $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
                    $attendance->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                    $attendance->save();
                }
            }
        }

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendResponse(null, 'Student attendance been submitted successfully');
        }
    }
    public function studentAttendanceStoreSecond(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            // 'id' => "required",
            'date' => "required",
            'attendance' => "required",
            'class' => "required",
            'subject' => 'required',
            'section' => "required"
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
            
            $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->select('id')->get();
        $attendance = SmSubjectAttendance::where('student_id', $request->id)->where('subject_id', $request->subject)->where('attendance_date', date('Y-m-d', strtotime($request->date)))->first();
       
        if (empty($attendance)) {
            foreach ($students as $student) {
                $attendance = SmSubjectAttendance::where('student_id', $student->id)->where('attendance_date', date('Y-m-d', strtotime($request->date)))->first();
                if ($attendance != "") {
                    $attendance->delete();
                }
                
                $attendance = new SmSubjectAttendance();
                $attendance->student_id = $student->id;
                $attendance->subject_id = $request->subject;
                $attendance->attendance_type =$request->attendance;
                $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
                $attendance->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                $attendance->save();
                
            }
        }
        $attendance = SmSubjectAttendance::where('student_id', $request->id)->where('subject_id', $request->subject)->where('attendance_date', date('Y-m-d', strtotime($request->date)))->first();
        if ($attendance != "") {
            $attendance->delete();
        }
        $attendance = new SmSubjectAttendance();
        $attendance->student_id = $request->id;
        $attendance->subject_id = $request->subject;
        $attendance->attendance_type = $request->attendance;
        $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
        $attendance->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        $attendance->save();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Student attendance been submitted successfully');
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
       
    }
}
