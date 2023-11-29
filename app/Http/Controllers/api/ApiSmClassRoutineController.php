<?php

namespace App\Http\Controllers\api;

use App\ApiBaseMethod;
use App\Http\Controllers\Controller;
use App\SmAcademicYear;
use App\SmAssignSubject;
use App\SmClass;
use App\SmClassRoom;
use App\SmClassRoutineUpdate;
use App\SmClassTime;
use App\SmStaff;
use App\SmStudent;
use App\SmWeekend;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Validator;

class ApiSmClassRoutineController extends Controller
{
    public function classRoutineSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
            // 'school_id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }

        try {
            $class_id = $request->class;
            $section_id = $request->section;
            $school_id = auth()->user()->school_id;

            $sm_weekends = SmWeekend::with('classRoutine')->where('school_id', $school_id)
                ->orderBy('order', 'ASC')
                ->where('active_status', 1)
                ->get(['id', 'name', 'order', 'is_weekend']);

            // return $sm_weekends;
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())
                ->where('school_id', $school_id)->get();

            $subjects = SmAssignSubject::where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('school_id', $school_id)
                ->distinct(['class_id', 'section_id', 'subject_id'])
                ->get()->map(function ($value) {
                return [
                    'id' => $value->subject->id,
                    'subject_name' => $value->subject->subject_name,
                    'subject_code' => $value->subject->subject_code,
                    'subject_type' => $value->subject->subject_type == 'T' ? 'Theory' : 'Practical',
                ];
            });

            // remove code unnecessary

            $rooms = SmClassRoom::where('active_status', 1) /* ->where('capacity','>=',$stds) */
                ->where('school_id', $school_id)
                ->get();

            $teachers = SmStaff::where('role_id', 4)->where('school_id', $school_id)->get(['id', 'full_name', 'user_id', 'school_id']);

            if (!$class_id) {
                Session::put('session_day_id', null);
            }

            return response()->json(compact('classes', 'teachers', 'rooms', 'subjects', 'class_id', 'section_id', 'sm_weekends'));
        } catch (\Exception$e) {

            //  return ApiBaseMethod::sendError('Error.', $e->getMessage());

            return response()->json(['message' => 'Operation Failed']);
        }
    }
    // {
    //     "day": "1",
    //     "class_id": "1",
    //     "section_id": "1",
    //     "routine": {
    //                 "1": {
    //                 "subject": "1",
    //                 "teacher_id": null,
    //                 "start_time": "12:37 PM",
    //                 "end_time": "12:37 PM",
    //                 "day_ids": [
    //                             "1",
    //                             "2",
    //                             "3",
    //                             "4",
    //                             "5",
    //                             "6",
    //                             "7"
    //                             ],
    //                 "room": "1"
    //                 }
    //              },
    //     }
    public function addNewClassRoutineStore(Request $request)
    {
        try {
            //  return  date("H:i", strtotime("04:25 PM"));
            // return response()->json($request->all());
            // change this method code for update class routine ->abu Nayem
            $request->validate([
                'class_id' => 'required',
                'section_id' => 'required',
                'day' => 'required',
            ]);

            $school_id = auth()->user()->school_id;

            SmClassRoutineUpdate::where('day', $request->day)->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)->where('academic_id', getAcademicId())
                ->where('school_id', $school_id)
                ->delete();

            foreach ($request->routine as $key => $routine_data) {
                if (!gv($routine_data, 'subject') || !gv($routine_data, 'start_time') || !gv($routine_data, 'end_time')) {
                    continue;
                }
                $days = gv($routine_data, 'day_ids') == null ? array($request->day) : gv($routine_data, 'day_ids', []);

                foreach ($days as $day) {
                    $exist_class_routine = SmClassRoutineUpdate::where('day', $day)
                        ->where('class_id', $request->class_id)
                        ->where('section_id', $request->section_id)
                        ->where('start_time', date('H:i:s', strtotime(gv($routine_data, 'start_time'))))
                        ->where('end_time', date('H:i:s', strtotime(gv($routine_data, 'end_time'))))
                        ->where('subject_id', gv($routine_data, 'subject'))
                        ->where('teacher_id', gv($routine_data, 'teacher_id'))
                        ->where('academic_id', getAcademicId())
                        ->where('school_id', $school_id)
                        ->first();

                    if ($exist_class_routine) {
                        continue;
                    }

                    $class_routine_time = SmClassRoutineUpdate::where('day', $day)
                        ->where('class_id', $request->class_id)
                        ->where('section_id', $request->section_id)
                        ->where('academic_id', getAcademicId())
                        ->where('school_id', $school_id)
                        ->first();
                    $timeInterval = [];
                    $startTimeToInteger = null;
                    if ($class_routine_time) {
                        $start_time = $class_routine_time->start_time;
                        $end_time = $class_routine_time->end_time;
                        $startTimeToInteger = str_replace(':', '', $start_time);
                        $endTimeToInteger = str_replace(':', '', $end_time);
                        $timeInterval = range($startTimeToInteger, $endTimeToInteger);
                    }
                    $requestStartTime = date('H:i:s', strtotime(gv($routine_data, 'start_time')));
                    if (in_array($requestStartTime, $timeInterval)) {
                        return response()->json(['error' => 'This Time Has another Class']);
                    }

                    $class_routine = new SmClassRoutineUpdate();
                    $class_routine->class_id = $request->class_id;
                    $class_routine->section_id = $request->section_id;
                    $class_routine->subject_id = gv($routine_data, 'subject');
                    $class_routine->teacher_id = gv($routine_data, 'teacher_id');
                    $class_routine->room_id = gv($routine_data, 'room');
                    $class_routine->start_time = date('H:i:s', strtotime(gv($routine_data, 'start_time')));
                    $class_routine->end_time = date('H:i:s', strtotime(gv($routine_data, 'end_time')));
                    $class_routine->is_break = gv($routine_data, 'is_break');
                    $class_routine->day = $day;
                    $class_routine->school_id = $school_id;
                    $class_routine->academic_id = getAcademicId();
                    $class_routine->save();
                }
            }

            Session::put('session_day_id', $request->day);
            return response()->json(['success' => 'Class routine has been updated successfully']);
            // return redirect()->back();
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());

            // return response()->json(['message' =>'Operation Failed']);
        }
    }
    public function dayWiseClassRoutine(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'day_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }

        $day_id = $request->day_id;
        $class_id = $request->class_id;
        $section_id = $request->section_id;
        $school_id = auth()->user()->school_id;
        $class_routines = SmClassRoutineUpdate::where('day', $day_id)->where('class_id', $class_id)->where('section_id', $section_id)->orderBy('start_time', 'ASC')->where('academic_id', getAcademicId())->where('school_id', $school_id)->get();

        $subjects = SmAssignSubject::where('class_id', $class_id)
            ->where('section_id', $section_id)
            ->where('school_id', $school_id)
            ->distinct(['class_id', 'section_id', 'subject_id'])
            ->get()->map(function ($value) {
            return [
                'id' => $value->subject->id,
                'subject_name' => $value->subject->subject_name,
                'subject_code' => $value->subject->subject_code,
                'subject_type' => $value->subject->subject_type == 'T' ? 'Theory' : 'Practical',
            ];
        });

        $stds = SmStudent::where('class_id', $class_id)->where('section_id', $section_id)
            ->where('academic_id', getAcademicId())->where('school_id', $school_id)->count();
        $rooms = SmClassRoom::where('active_status', 1)->where('capacity', '>=', $stds)
            ->where('school_id', $school_id)
            ->get();
        $teachers = SmStaff::where('role_id', 4)->where('school_id', $school_id)->get(['id', 'full_name', 'user_id', 'school_id']);
        $sm_weekends = SmWeekend::where('school_id', $school_id)
            ->orderBy('order', 'ASC')
            ->where('active_status', 1)
            ->get(['id', 'name', 'order', 'is_weekend']);
        return response()->json(compact('day_id', 'class_routines', 'sm_weekends', 'subjects', 'rooms', 'teachers', 'section_id', 'class_id'));
    }

    public function studentClassRoutine(Request $request, $user_id, $record_id = null)
    {
        try {
            $student_detail = SmStudent::select('id', 'full_name')
                ->where('user_id', $user_id)
                ->first();
            $record = studentRecords(null, $student_detail->id)->where('id', $record_id)->first();
            $class_id = $record->class_id;
            $section_id = $record->section_id;

            //return $student_detail;

            $school_id = auth()->user()->school_id;

            $class_routines = SmClassRoutineUpdate::with('weekend', 'classRoom', 'subject', 'teacherDetail', 'class', 'section')->where('class_id', $class_id)->where('section_id', $section_id)
                ->where('school_id', $school_id)->get()->map(function ($value) {
                return [
                    'id' => $value->id,
                    'day' => $value->weekend ? $value->weekend->name : '',
                    'room' => $value->classRoom ? $value->classRoom->room_no : '',
                    'subject' => $value->subject ? $value->subject->subject_name : '',
                    'teacher' => $value->teacherDetail ? $value->teacherDetail->full_name : '',
                    'class' => $value->class ? $value->class->class_name : '',
                    'section' => $value->section ? $value->section->section_name : '',
                    'start_time' => date('h:i A', strtotime($value->start_time)),
                    'end_time' => date('h:i A', strtotime($value->end_time)),
                    'break' => $value->is_break ? 'Yes' : 'No',

                ];
            });

            return response()->json(compact('student_detail', 'class_routines'));
        } catch (\Exception$e) {

            // return redirect()->back();
            return ApiBaseMethod::sendError('Error.', $e->getMessage());

        }
    }
    public function teacherClassRoutine($user_id, $school_id = null)
    {
        try {

            $staff_detail = SmStaff::select('id', 'full_name', 'role_id')
                ->where('user_id', $user_id)
                ->first();
            if ($staff_detail->role_id !=4) {
                return response()->json(['message'=>'You Are not teacher']);
            }
            $teacher_id = $staff_detail->id;

            $school_id = $school_id !=null ? $school_id : auth()->user()->school_id;

            $class_routines = SmClassRoutineUpdate::with('weekend', 'classRoom', 'subject', 'teacherDetail', 'class', 'section')->where('teacher_id', $teacher_id)->where('school_id', $school_id)->get()->map(function ($value) {
                return [
                    'id' => $value->id,
                    'day' => $value->weekend ? $value->weekend->name : '',
                    'room' => $value->classRoom ? $value->classRoom->room_no : '',
                    'subject' => $value->subject ? $value->subject->subject_name : '',
                    'teacher' => $value->teacherDetail ? $value->teacherDetail->full_name : '',
                    'class' => $value->class ? $value->class->class_name : '',
                    'section' => $value->section ? $value->section->section_name : '',
                    'start_time' => date('h:i A', strtotime($value->start_time)),
                    'end_time' => date('h:i A', strtotime($value->end_time)),
                    'break' => $value->is_break ? 'Yes' : 'No',

                ];
            });

            return response()->json(compact('staff_detail', 'class_routines'));
        } catch (\Exception$e) {

            // return redirect()->back();
            return ApiBaseMethod::sendError('Error.', $e->getMessage());

        }
    }
    public function saasTeacherClassRoutine($user_id, $school_id)
    {
        $this->teacherClassRoutine($user_id, $school_id);
    }
    public function teacherClassRoutineReportSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'teacher_id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        $teacher_id = $request->teacher_id;
        $this->teacherClassRoutine($teacher_id);
    }

    public function classRoutineReportSearch(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'class' => 'required',
                'section' => 'required',
                // 'school_id' => 'required',
            ]);

            if ($validator->fails()) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
                }
            }
            $class_id = $request->class;
            $section_id = $request->section;
            $school_id = auth()->user()->school_id;

            $class_routines = SmClassRoutineUpdate::with('weekend', 'classRoom', 'subject', 'teacherDetail', 'class', 'section')->where('class_id', $class_id)->where('section_id', $section_id)
                ->where('school_id', $school_id)->get()->map(function ($value) {
                return [
                    'id' => $value->id,
                    'day' => $value->weekend ? $value->weekend->name : '',
                    'room' => $value->classRoom ? $value->classRoom->room_no : '',
                    'subject' => $value->subject ? $value->subject->subject_name : '',
                    'teacher' => $value->teacherDetail ? $value->teacherDetail->full_name : '',
                    'class' => $value->class ? $value->class->class_name : '',
                    'section' => $value->section ? $value->section->section_name : '',
                    'start_time' => date('h:i A', strtotime($value->start_time)),
                    'end_time' => date('h:i A', strtotime($value->end_time)),
                    'break' => $value->is_break ? 'Yes' : 'No',

                ];
            });
            return response()->json(compact('staff_detail', 'class_routines'));
        } catch (\Throwable$th) {
            return ApiBaseMethod::sendError('Error.', $th->getMessage());
        }
    }
    public function teacherList()
    {
        $teachers = SmStaff::where('role_id', 4)->where('school_id', auth()->user()->school_id)->get(['id', 'full_name', 'user_id', 'school_id']);
        return response()->json(['teachers' => $teachers]);
    }


    public function sassClassRoutine(Request $request, $school_id, $user_id = null, $record_id = null)
    {


        $student_detail = SmStudent::select('id', 'full_name')->where('user_id', $user_id)->where('school_id', $school_id)->first();
        $record = studentRecords(null, $student_detail->id, $school_id)->where('id', $record_id)->first();
        $class_id = $record->class_id;
        $section_id = $record->section_id;

        $class_routines = SmClassRoutineUpdate::with('weekend', 'classRoom', 'subject', 'teacherDetail', 'class', 'section')->where('class_id', $class_id)->where('section_id', $section_id)
        ->where('school_id', $school_id)->get()->map(function ($value) {
            return [
                'id' => $value->id,
                'day' => $value->weekend ? $value->weekend->name : '',
                'room' => $value->classRoom ? $value->classRoom->room_no : '',
                'subject' => $value->subject ? $value->subject->subject_name : '',
                'teacher' => $value->teacherDetail ? $value->teacherDetail->full_name : '',
                'class' => $value->class ? $value->class->class_name : '',
                'section' => $value->section ? $value->section->section_name : '',
                'start_time' => date('h:i A', strtotime($value->start_time)),
                'end_time' => date('h:i A', strtotime($value->end_time)),
                'break' => $value->is_break ? 'Yes' : 'No',

            ];
        });

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $data['student_detail'] = $student_detail->toArray();
            $data['class_routines'] = $class_routines->toArray();

            return ApiBaseMethod::sendResponse($data, null);
        }


    }

    public function sectionRoutine(Request $request, $user_id, $class, $section)
    {
        try {

            $staff_detail = SmStaff::select('id', 'full_name')
                ->where('user_id', $user_id)
                ->first();
            if ($staff_detail->role_id !=4) {
                return response()->json(['message'=>'You Are not teacher']);
            }
            $teacher_id = $staff_detail->id;

            $school_id = auth()->user()->school_id;

            $class_routines = SmClassRoutineUpdate::with('weekend', 'classRoom', 'subject', 'teacherDetail', 'class', 'section')->where('teacher_id', $teacher_id)->where('class_id', $class)->where('section_id', $section)->where('school_id', $school_id)->get()->map(function ($value) {
                return [
                    'id' => $value->id,
                    'day' => $value->weekend ? $value->weekend->name : '',
                    'room' => $value->classRoom ? $value->classRoom->room_no : '',
                    'subject' => $value->subject ? $value->subject->subject_name : '',
                    'teacher' => $value->teacherDetail ? $value->teacherDetail->full_name : '',
                    'class' => $value->class ? $value->class->class_name : '',
                    'section' => $value->section ? $value->section->section_name : '',
                    'start_time' => date('h:i A', strtotime($value->start_time)),
                    'end_time' => date('h:i A', strtotime($value->end_time)),
                    'break' => $value->is_break ? 'Yes' : 'No',

                ];
            });

            return response()->json(compact('staff_detail', 'class_routines'));
        } catch (\Exception$e) {

            // return redirect()->back();
            return ApiBaseMethod::sendError('Error.', $e->getMessage());

        } 
    }
    public function saas_sectionRoutine(Request $request, $school_id, $user_id, $class, $section)
    {
        try {

            $staff_detail = SmStaff::select('id', 'full_name')
                ->where('user_id', $user_id)
                ->first();
            if ($staff_detail->role_id !=4) {
                return response()->json(['message'=>'You Are not teacher']);
            }
            $teacher_id = $staff_detail->id;

            $school_id = $school_id !=null ? $school_id : auth()->user()->school_id;

            $class_routines = SmClassRoutineUpdate::with('weekend', 'classRoom', 'subject', 'teacherDetail', 'class', 'section')->where('teacher_id', $teacher_id)->where('class_id', $class)->where('section_id', $section)->where('school_id', $school_id)->get()->map(function ($value) {
                return [
                    'id' => $value->id,
                    'day' => $value->weekend ? $value->weekend->name : '',
                    'room' => $value->classRoom ? $value->classRoom->room_no : '',
                    'subject' => $value->subject ? $value->subject->subject_name : '',
                    'teacher' => $value->teacherDetail ? $value->teacherDetail->full_name : '',
                    'class' => $value->class ? $value->class->class_name : '',
                    'section' => $value->section ? $value->section->section_name : '',
                    'start_time' => date('h:i A', strtotime($value->start_time)),
                    'end_time' => date('h:i A', strtotime($value->end_time)),
                    'break' => $value->is_break ? 'Yes' : 'No',

                ];
            });

            return response()->json(compact('staff_detail', 'class_routines'));
        } catch (\Exception$e) {

            // return redirect()->back();
            return ApiBaseMethod::sendError('Error.', $e->getMessage());

        } 
    }
}
