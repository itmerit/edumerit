<?php

namespace App\Http\Controllers;

use App\SmClass;
use App\SmStaff;
use App\SmSchool;
use App\SmSection;
use App\SmStudent;
use App\SmWeekend;
use App\YearCheck;
use App\SmClassRoom;
use App\SmClassTime;
use App\ApiBaseMethod;
use App\SmAcademicYear;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use App\SmClassRoutineUpdate;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmClassRoutineNewController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function classRoutine(Request $request)
    {

        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($classes, null);
            }
            return view('backEnd.academics.class_routine_new', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function classRoutinePrint($class, $section)
    {

        // try {
            $class_times = SmClassTime::where('type', 'class')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $class_id = $class;
            $section_id = $section;
            $academic_year=SmAcademicYear::find(getAcademicId());

            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();
            
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            // $customPaper = array(0, 0, 700.00, 1500.80);
            $pdf = \PDF::loadView(
                'backEnd.academics.class_routine_print',
                [
                    'classes' => $classes,
                    'class_times' => $class_times,
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'academic_year' => $academic_year,
                    'sm_weekends' => $sm_weekends,
                    'section' => SmSection::find($section_id),
                    'class' => SmClass::find($class_id),
                ]
            )->setPaper('A4', 'landscape');
            return $pdf->stream('class_routine.pdf');
        // } catch (\Exception $e) {
        //     Toastr::error('Operation Failed', 'Failed');
        //     return redirect()->back();
        // }
    }

    public function classRoutineSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
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
            $class_times = SmClassTime::where('academic_id', getAcademicId())
						->where('school_id', Auth::user()->school_id)
						->where('type', 'class')
						->orderBy('start_time', 'asc')
                        ->get();
                        
            $class_id = $request->class;
            $section_id = $request->section;

            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)
            ->orderBy('order', 'ASC')
            ->where('active_status', 1)
            ->get();
            // return $sm_weekends;
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['class_times'] = $class_times->toArray();
                $data['class_id'] = $class_id;
                $data['section_id'] = $section_id;
                $data['sm_weekends'] = $sm_weekends;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.academics.class_routine_new', compact('classes', 'class_times', 'class_id', 'section_id', 'sm_weekends'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function addNewClassRoutine($class_time_id, $day, $class_id, $section_id)
    {

        try {
            $assinged_subjects = SmClassRoutineUpdate::select('subject_id')->where('class_id', $class_id)->where('section_id', $section_id)->where('day', $day)
                ->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $assinged_subject = [];
            foreach ($assinged_subjects as $value) {
                $assinged_subject[] = $value->subject_id;
            }

            $assinged_rooms = SmClassRoutineUpdate::select('room_id')->where('class_period_id', $class_time_id)->where('day', $day)
                ->where('school_id',Auth::user()->school_id)->get();

            $assinged_room = [];
            foreach ($assinged_rooms as $value) {
                $assinged_room[] = $value->room_id;
            }
            $stds = SmStudent::where('class_id', $class_id)->where('section_id', $section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->count();
            $rooms = SmClassRoom::where('active_status', 1)->where('capacity','>=',$stds)->where('school_id',Auth::user()->school_id)->get();
            $subjects = SmAssignSubject::where('class_id', $class_id)->where('section_id', $section_id)->where('school_id',Auth::user()->school_id)->get();

            return view('backEnd.academics.add_new_class_routine_form', compact('rooms', 'subjects', 'day', 'class_time_id', 'class_id', 'section_id', 'assinged_subject', 'assinged_room'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function addNewClassRoutineEdit($class_time_id, $day, $class_id, $section_id, $subject_id, $room_id, $assigned_id, $teacher_id)
    {

        try {
            $assinged_subjects = SmClassRoutineUpdate::select('subject_id')->where('class_id', $class_id)->where('section_id', $section_id)->where('day', $day)->where('subject_id', '!=', $subject_id)->where('school_id',Auth::user()->school_id)->get();

            $assinged_subject = [];
            foreach ($assinged_subjects as $value) {
                $assinged_subject[] = $value->subject_id;
            }

            $assinged_rooms = SmClassRoutineUpdate::select('room_id')->where('room_id', '!=', $room_id)->where('class_period_id', $class_time_id)->where('day', $day)->where('school_id',Auth::user()->school_id)->get();

            $assinged_room = [];
            foreach ($assinged_rooms as $value) {
                $assinged_room[] = $value->room_id;
            }
            $rooms = SmClassRoom::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $teacher_detail = SmStaff::select('id', 'full_name')->where('id', $teacher_id)->first();

            $subjects = SmAssignSubject::where('class_id', $class_id)->where('section_id', $section_id)->where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.academics.add_new_class_routine_form', compact('rooms', 'subjects', 'day', 'class_time_id', 'class_id', 'section_id', 'assinged_subject', 'assinged_room', 'subject_id', 'room_id', 'assigned_id', 'teacher_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function addNewClassRoutineStore(Request $request)
    {
        try {
            if (!isset($request->assigned_id)) {

                $days=$request->day_ids;
                    if($request->day_ids){
                     foreach ($days as $day) {
                           
                         $check = SmClassRoutineUpdate::where('class_id', $request->class_id)
                                                     ->where('section_id', $request->section_id)->where('subject_id', $request->subject)
                                                     ->where('room_id', $request->room)->where('class_period_id', $request->class_time_id)
                                                     ->where('day', $day)->first();

                   
                            $class_routine = new SmClassRoutineUpdate();
                            $class_routine->class_id = $request->class_id;
                            $class_routine->section_id = $request->section_id;
                            $class_routine->subject_id = $request->subject;
                            $class_routine->teacher_id = $request->teacher_id;
                            $class_routine->room_id = $request->room;
                            $class_routine->class_period_id = $request->class_time_id;
                            $class_routine->day = $day;
                            $class_routine->school_id = Auth::user()->school_id;
                            $class_routine->academic_id = getAcademicId();
                                
                                   if ($check) {

                                            continue;
                                    }
                            $class_routine->save();               
                      
                         }
                     }else{
                   
                         $check = SmClassRoutineUpdate::where('class_id', $request->class_id)
                                                     ->where('section_id', $request->section_id)
                                                     ->where('subject_id', $request->subject)
                                                     ->where('room_id', $request->room)
                                                     ->where('class_period_id', $request->class_time_id)
                                                     ->where('day',$request->day)
                                                     ->first();

                         if(empty($check)){
                                $class_routine = new SmClassRoutineUpdate();
                                $class_routine->class_id = $request->class_id;
                                $class_routine->section_id = $request->section_id;
                                $class_routine->subject_id = $request->subject;
                                $class_routine->teacher_id = $request->teacher_id;
                                $class_routine->room_id = $request->room;
                                $class_routine->class_period_id = $request->class_time_id;
                                $class_routine->day = $request->day;
                                $class_routine->school_id = Auth::user()->school_id;
                                $class_routine->academic_id = getAcademicId();
                                $class_routine->save();
                         }

                     }
                  Toastr::success('Class routine has been assigned successfully', 'Success');
                  
            } else {
                $class_routine = SmClassRoutineUpdate::find($request->assigned_id);
                $class_routine->class_id = $request->class_id;
                $class_routine->section_id = $request->section_id;
                $class_routine->subject_id = $request->subject;
                $class_routine->teacher_id = $request->teacher_id;
                $class_routine->room_id = $request->room;
                $class_routine->class_period_id = $request->class_time_id;
                $class_routine->day = $request->day;
                $class_routine->save();
                // \Session::flash('success', 'Class routine has been updated successfully');
                Toastr::success('Class routine has been updated successfully', 'Success');
            }

            //$class_times = SmClassTime::all();
            $class_id = $request->class_id;
            $section_id = $request->section_id;

            //$classes = SmClass::where('active_status', 1)->get();
            //return view('backEnd.academics.class_routine_new', compact('classes', 'class_times', 'class_id', 'section_id'));

            // return redirect('class-routine-new/' . $class_id . '/' . $section_id);
            return redirect('class-routine-new/' . $class_id . '/' . $section_id);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function classRoutineRedirect($class_id, $section_id)
    {

        try {
            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();
            $class_times = SmClassTime::where('type', 'class')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.academics.class_routine_new', compact('classes', 'class_times', 'class_id', 'section_id', 'sm_weekends'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getClassTeacherAjax(Request $request)
    {

        try {
            $subject_teacher = SmAssignSubject::select('teacher_id')->where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('subject_id', $request->subject)->first();
            $teacher_detail = '';
            $i = 0;
            if ($subject_teacher->teacher_id != "") {
                if ($request->update_teacher_id == "") {

                    $already_assigned = SmClassRoutineUpdate::where('class_period_id', $request->class_time_id)->where('day', $request->day)->where('teacher_id', $subject_teacher->teacher_id)->first();
                } else {
                    $already_assigned = SmClassRoutineUpdate::where('teacher_id', '!=', $request->update_teacher_id)->where('class_period_id', $request->class_time_id)->where('day', $request->day)->where('teacher_id', $subject_teacher->teacher_id)->first();
                }

                $i++;

                if ($already_assigned == "") {
                    $teacher_detail = SmStaff::where('id', $subject_teacher->teacher_id)->first();
                }
            }

            return response()->json([$teacher_detail, $i]);
        } catch (\Exception $e) {
            return response()->json("",404);
        }
    }

    public function classRoutineReport(Request $request)
    {

        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($classes, null);
            }
            return view('backEnd.reports.class_routine_report', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function classRoutineReportSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
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
            $class_times = SmClassTime::where('type', 'class')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $class_id = $request->class;
            $section_id = $request->section;
            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $sm_routine_updates = $classes->where('id',$request->class)->first()->routineUpdates->where('section_id',$section_id);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['class_times'] = $class_times->toArray();
                $data['class_id'] = $class_id;
                $data['section_id'] = $section_id;
                $data['sm_weekends'] = $sm_weekends->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.reports.class_routine_report', compact('classes', 'class_times', 'class_id', 'section_id','sm_routine_updates', 'sm_weekends'));
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
//            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function teacherClassRoutineReport(Request $request)
    {

        try {
            $teachers = SmStaff::select('id', 'full_name')->where('active_status', 1)
                ->where(function($q)  {                
                    $q->where('role_id', 4)->orWhere('previous_role_id', 4);             
                })->where('school_id',Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($teachers, null);
            }
            return view('backEnd.reports.teacher_class_routine_report', compact('teachers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function teacherClassRoutineReportSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'teacher' => 'required',
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
            $class_times = SmClassTime::where('type', 'class')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $teacher_id = $request->teacher;
            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();
            $teachers = SmStaff::where('role_id', 4)->select('id', 'full_name')->where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['class_times'] = $class_times->toArray();
                $data['teacher_id'] = $teacher_id;
                $data['sm_weekends'] = $sm_weekends->toArray();
                $data['teachers'] = $teachers->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.reports.teacher_class_routine_report', compact('class_times', 'teacher_id', 'sm_weekends', 'teachers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteClassRoutineModal($id)
    {

        try {
            return view('backEnd.academics.delete_class_routine', compact('id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteClassRoutine($id)
    {

        try {

            // $class_routine = SmClassRoutineUpdate::find($id);
             if (checkAdmin()) {
                $class_routine = SmClassRoutineUpdate::find($id);
            }else{
                $class_routine = SmClassRoutineUpdate::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $class_id = $class_routine->class_id;
            $section_id = $class_routine->section_id;
            $result = $class_routine->delete();
            if ($result) {
                Toastr::success('Class routine has been deleted successfully', 'Success');
            } else {
                Toastr::error('Operation Failed', 'Failed');
            }
            return redirect('class-routine-new/' . $class_id . '/' . $section_id);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function getOtherDaysAjax(Request $request){

        try {
            $subject_teacher = SmAssignSubject::select('teacher_id')->where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('subject_id', $request->subject)->first();         

           $assgin_days = SmClassRoutineUpdate::query();
           $assgin_days->where('class_period_id',$request->class_time_id)
                        ->where('class_id',$request->class_id)
                        ->where('section_id',$request->section_id);
           $assgin_day_ids=$assgin_days->select('day')->get();

            $days=SmWeekend::query();
            $days->whereNotIn('id',$assgin_day_ids);
            $days=$days->where('is_weekend',0)->orderBy('order', 'ASC')->where('active_status', 1)->get();
             return response()->json([$days]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
           

   }

}