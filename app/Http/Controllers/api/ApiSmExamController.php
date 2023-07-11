<?php

namespace App\Http\Controllers\api;

use App\ApiBaseMethod;
use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Scopes\StatusAcademicSchoolScope;
use App\SmAcademicYear;
use App\SmAssignSubject;
use App\SmClassSection;
use App\SmExam;
use App\SmExamSchedule;
use App\SmExamSetup;
use App\SmFeesMaster;
use App\SmGeneralSettings;
use App\SmHoliday;
use App\SmOnlineExam;
use App\SmStudent;
use App\YearCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiSmExamController extends Controller
{
    public function examListApi(Request $request, $user_id, $record_id)
    {

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            $student_id = SmStudent::where('user_id', $user_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();

            $exam_List = DB::table('sm_exam_types')
                ->join('sm_exams', 'sm_exams.exam_type_id', '=', 'sm_exam_types.id')
                ->where('sm_exams.class_id', '=', $record->class_id)
                ->where('sm_exams.section_id', '=', $record->section_id)
                ->distinct()
                ->select('sm_exam_types.id as exam_id', 'sm_exam_types.title as exam_name')
                ->get();

            return ApiBaseMethod::sendResponse($exam_List, null);
        }
    }
    public function saas_examListApi(Request $request, $school_id, $user_id, $record_id)
    {

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            $student_id = SmStudent::where('user_id', $user_id)->where('school_id', $school_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();
            $exam_List = DB::table('sm_exam_types')
                ->join('sm_exams', 'sm_exams.exam_type_id', '=', 'sm_exam_types.id')
                ->where('sm_exams.class_id', '=', @$record->class_id)
                ->where('sm_exams.section_id', '=', @$record->section_id)
                ->distinct()
                ->select('sm_exam_types.id as exam_id', 'sm_exam_types.title as exam_name')
                ->get();

            return ApiBaseMethod::sendResponse($exam_List, null);
        }
    }
    public function examScheduleApi(Request $request, $user_id, $exam_id, $record_id)
    {

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            $student_id = SmStudent::where('user_id', $user_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();
            $exam_schedule = DB::table('sm_exam_schedules')
                ->join('sm_exam_types', 'sm_exam_types.id', '=', 'sm_exam_schedules.exam_term_id')

                ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_exam_schedules.subject_id')
                ->join('sm_class_rooms', 'sm_class_rooms.id', '=', 'sm_exam_schedules.room_id')
                ->join('sm_class_times', 'sm_class_times.id', '=', 'sm_exam_schedules.exam_period_id')

                ->where('sm_exam_schedules.exam_term_id', '=', $exam_id)
                ->where('sm_exam_schedules.school_id', '=', $record->school_id)
                ->where('sm_exam_schedules.class_id', '=', $record->class_id)
                ->where('sm_exam_schedules.section_id', '=', $record->section_id)

                ->where('sm_exam_schedules.active_status', '=', 1)

                ->select('sm_exam_types.id', 'sm_exam_types.title as exam_name', 'sm_subjects.subject_name', 'date', 'sm_class_rooms.room_no', 'sm_class_times.start_time', 'sm_class_times.end_time')

                ->get();

            return ApiBaseMethod::sendResponse($exam_schedule, null);
        }
    }
    public function saas_examScheduleApi(Request $request, $school_id, $user_id, $exam_id, $record_id)
    {

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            $student_id = SmStudent::where('user_id', $user_id)->where('school_id', $school_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();

            $exam_schedule = DB::table('sm_exam_schedules')
                ->join('sm_exam_types', 'sm_exam_types.id', '=', 'sm_exam_schedules.exam_term_id')

                ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_exam_schedules.subject_id')
                ->join('sm_class_rooms', 'sm_class_rooms.id', '=', 'sm_exam_schedules.room_id')
                ->join('sm_class_times', 'sm_class_times.id', '=', 'sm_exam_schedules.exam_period_id')

                ->where('sm_exam_schedules.exam_term_id', '=', $exam_id)
                ->where('sm_exam_schedules.school_id', '=', @$record->school_id)
                ->where('sm_exam_schedules.class_id', '=', @$record->class_id)
                ->where('sm_exam_schedules.section_id', '=', @$record->section_id)

                ->where('sm_exam_schedules.active_status', '=', 1)

                ->select('sm_exam_types.id', 'sm_exam_types.title as exam_name', 'sm_subjects.subject_name', 'date', 'sm_class_rooms.room_no', 'sm_class_times.start_time', 'sm_class_times.end_time')

                ->where('sm_exam_schedules.school_id', $school_id)->get();

            return ApiBaseMethod::sendResponse($exam_schedule, null);
        }
    }
    public function examResult_Api(Request $request, $user_id, $exam_id, $record_id)
    {
        $data = [];

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            $student_id = SmStudent::where('user_id', $user_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();

            $exam = \App\SmExamType::find($exam_id);
            $get_results = \App\SmStudent::getExamResult(@$exam->id, @$record);
            $result = [];

            if($get_results){
                foreach($get_results as $mark){
                    $result[] =  [
                    'id' => $mark->id,
                    'exam_name' => @$exam->title,
                    'subject_name' => @$mark->subject->subject_name,
                    'obtained_marks' => @$mark->total_marks,
                    'total_marks' => @subjectFullMark($mark->exam_type_id, $mark->subject_id, $record->class_id, $record->section_id),
                    'grade' => @$mark->total_gpa_grade,
                    ];
                }
            }

            $data['exam_result'] = $result;
            $data['pass_marks'] = 0;

            return ApiBaseMethod::sendResponse($data, null);
        }
    }
    public function saas_examResult_Api(Request $request, $school_id, $user_id, $exam_id, $record_id)
    {
            $data = [];
            $student_id = SmStudent::where('user_id', $user_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();
            $exam = \App\SmExamType::find($exam_id);
            $get_results = \App\SmStudent::getExamResult(@$exam->id, @$record);
            $result = [];

            if($get_results){
                foreach($get_results as $mark){
                    $result[] =  [
                    'id' => $mark->id,
                    'exam_name' => @$exam->title,
                    'subject_name' => @$mark->subject->subject_name,
                    'obtained_marks' => @$mark->total_marks,
                    'total_marks' => @subjectFullMark($mark->exam_type_id, $mark->subject_id, $record->class_id, $record->section_id),
                    'grade' => @$mark->total_gpa_grade,
                    ];
                }
            }

            $data['exam_result'] = $result;
            $data['pass_marks'] = 0;

            return ApiBaseMethod::sendResponse($data, null);

    }
    public function saas_feesMasterUpdate(Request $request, $school_id)
    {
        $input = $request->all();
        if ($request->fees_group == "" || $request->fees_group != 1 && $request->fees_group != 2) {

            $validator = Validator::make($input, [
                'fees_group' => "required",
                'fees_type' => "required",
                'date' => "required",
                'amount' => "required",
            ]);
        } else {
            $validator = Validator::make($input, [
                'fees_group' => "required",
                'fees_type' => "required",
                'date' => "required",
            ]);
        }
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }

        $combination = SmFeesMaster::where('fees_group_id', $request->fees_group)->where('fees_type_id', $request->fees_type)->where('school_id', $school_id)->count();

        if ($combination == 0) {
            $fees_master = SmFeesMaster::where('school_id', $school_id)->find($request->id);
            $fees_master->fees_group_id = $request->fees_group;
            $fees_master->fees_type_id = $request->fees_type;
            $fees_master->date = date('Y-m-d', strtotime($request->date));
            if ($request->fees_group != 1 && $request->fees_group != 2) {
                $fees_master->amount = $request->amount;
            } else {
                $fees_master->amount = null;
            }
            $fees_master->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $result = $fees_master->save();
            if ($result) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendResponse(null, 'Fees Master updated successfully');
                }
            } else {
                return ApiBaseMethod::sendError('Operation Failed.', $validator->errors());
            }
        } else {
            return ApiBaseMethod::sendError('Operation Failed.', $validator->errors());
        }
    }
    public function NewExamSetup(Request $request)
    {

        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'class_ids' => 'required',
                'subjects_ids' => 'required|array',
                'exams_types' => 'required|array',
                'exam_marks' => "required|min:0",
            ]);
        }
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {

            $sections = SmClassSection::where('class_id', $request->class_ids)->get();

            $exist_check = SmExam::where('class_id', '=', $request->class_ids)->count();

            if ($exist_check == 0) {

                foreach ($request->exams_types as $exam_type_id) {

                    foreach ($sections as $section) {

                        $subject_for_sections = SmAssignSubject::where('class_id', $request->class_ids)->where('section_id', $section->section_id)->get();

                        $eligible_subjects = [];

                        foreach ($subject_for_sections as $subject_for_section) {
                            $eligible_subjects[] = $subject_for_section->subject_id;
                        }

                        foreach ($request->subjects_ids as $subject_id) {

                            if (in_array($subject_id, $eligible_subjects)) {
                                $exam = new SmExam();
                                $exam->exam_type_id = $exam_type_id;
                                $exam->class_id = $request->class_ids;
                                $exam->section_id = $section->section_id;
                                $exam->subject_id = $subject_id;
                                $exam->exam_mark = $request->exam_marks;
                                $exam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                $exam->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                                $exam->save();

                                $exam->toArray();

                                $exam_term_id = $exam->id;

                                $length = count($request->exam_title);

                                for ($i = 0; $i < $length; $i++) {

                                    $ex_title = $request->exam_title[$i];
                                    $ex_mark = $request->exam_mark[$i];

                                    $newSetupExam = new SmExamSetup();
                                    $newSetupExam->exam_id = $exam->id;
                                    $newSetupExam->class_id = $request->class_ids;
                                    $newSetupExam->section_id = $section->section_id;
                                    $newSetupExam->subject_id = $subject_id;
                                    $newSetupExam->exam_term_id = $exam_type_id;
                                    $newSetupExam->exam_title = $ex_title;
                                    $newSetupExam->exam_mark = $ex_mark;
                                    $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                    $newSetupExam->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                                    $result = $newSetupExam->save();
                                }
                            }
                        }
                    }
                }
            } else {
                return ApiBaseMethod::sendResponse(null, 'Exam setup exist');
            }
            DB::commit();

            return ApiBaseMethod::sendResponse(null, 'Exam setup done');
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Operation Failed.', $validator->errors());
        }
    }
    public function saas_NewExamSetup(Request $request, $school_id)
    {

        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'class_ids' => 'required',
                'subjects_ids' => 'required|array',
                'exams_types' => 'required|array',
                'exam_marks' => "required|min:0",
            ]);
        }
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {

            $sections = SmClassSection::where('class_id', $request->class_ids)->where('school_id', $school_id)->get();

            $exist_check = SmExam::where('class_id', '=', $request->class_ids)->where('school_id', $school_id)->count();

            if ($exist_check == 0) {

                foreach ($request->exams_types as $exam_type_id) {

                    foreach ($sections as $section) {

                        $subject_for_sections = SmAssignSubject::where('class_id', $request->class_ids)->where('section_id', $section->section_id)->where('school_id', $school_id)->get();

                        $eligible_subjects = [];

                        foreach ($subject_for_sections as $subject_for_section) {
                            $eligible_subjects[] = $subject_for_section->subject_id;
                        }

                        foreach ($request->subjects_ids as $subject_id) {

                            if (in_array($subject_id, $eligible_subjects)) {
                                $exam = new SmExam();
                                $exam->exam_type_id = $exam_type_id;
                                $exam->class_id = $request->class_ids;
                                $exam->section_id = $section->section_id;
                                $exam->subject_id = $subject_id;
                                $exam->exam_mark = $request->exam_marks;
                                $exam->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                                $exam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');

                                $exam->save();

                                $exam->toArray();

                                $exam_term_id = $exam->id;

                                $length = count($request->exam_title);

                                for ($i = 0; $i < $length; $i++) {

                                    $ex_title = $request->exam_title[$i];
                                    $ex_mark = $request->exam_mark[$i];

                                    $newSetupExam = new SmExamSetup();
                                    $newSetupExam->exam_id = $exam->id;
                                    $newSetupExam->class_id = $request->class_ids;
                                    $newSetupExam->section_id = $section->section_id;
                                    $newSetupExam->subject_id = $subject_id;
                                    $newSetupExam->exam_term_id = $exam_type_id;
                                    $newSetupExam->exam_title = $ex_title;
                                    $newSetupExam->exam_mark = $ex_mark;
                                    $newSetupExam->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                                    $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                    $result = $newSetupExam->save();
                                }
                            }
                        }
                    }
                }
            } else {
                return ApiBaseMethod::sendResponse(null, 'Exam setup exist');
            }
            DB::commit();

            return ApiBaseMethod::sendResponse(null, 'Exam setup done');
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Operation Failed.', $validator->errors());
        }
    }
    public function NewExamSchedule(Request $request)
    {

        if ($request->assigned_id == "") {
            $check_date = SmExamSchedule::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('exam_term_id', $request->exam_term_id)->where('date', date('Y-m-d', strtotime($request->date)))->where('exam_period_id', $request->exam_period_id)->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->get();
        } else {
            $check_date = SmExamSchedule::where('id', '!=', $request->assigned_id)->where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('exam_term_id', $request->exam_term_id)->where('date', date('Y-m-d', strtotime($request->date)))->where('exam_period_id', $request->exam_period_id)->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->get();
        }

        $holiday_check = SmHoliday::where('from_date', '<=', date('Y-m-d', strtotime($request->date)))->where('to_date', '>=', date('Y-m-d', strtotime($request->date)))->first();

        if ($holiday_check != "") {
            $from_date = date('jS M, Y', strtotime($holiday_check->from_date));
            $to_date = date('jS M, Y', strtotime($holiday_check->to_date));
        } else {
            $from_date = '';
            $to_date = '';
        }
    }
    public function saas_NewExamSchedule(Request $request, $school_id)
    {

        if ($request->assigned_id == "") {
            $check_date = SmExamSchedule::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('exam_term_id', $request->exam_term_id)->where('date', date('Y-m-d', strtotime($request->date)))->where('exam_period_id', $request->exam_period_id)->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->where('school_id', $school_id)->get();
        } else {
            $check_date = SmExamSchedule::where('id', '!=', $request->assigned_id)->where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('exam_term_id', $request->exam_term_id)->where('date', date('Y-m-d', strtotime($request->date)))->where('exam_period_id', $request->exam_period_id)->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->where('school_id', $school_id)->get();
        }

        $holiday_check = SmHoliday::where('from_date', '<=', date('Y-m-d', strtotime($request->date)))->where('to_date', '>=', date('Y-m-d', strtotime($request->date)))->where('school_id', $school_id)->first();

        if ($holiday_check != "") {
            $from_date = date('jS M, Y', strtotime($holiday_check->from_date));
            $to_date = date('jS M, Y', strtotime($holiday_check->to_date));
        } else {
            $from_date = '';
            $to_date = '';
        }
    }
    public function examResultApi(Request $request, $user_id, $exam_id, $record_id)
    {

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $student_id = SmStudent::where('user_id', $user_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();

            $student_exams = DB::table('sm_online_exams')
                ->where('class_id', $record->class_id)
                ->where('section_id', $record->section_id)
                ->where('school_id', $record->school_id)
                ->select('sm_online_exams.title as exam_name', 'sm_online_exams.id as exam_id')
                ->get();

            $exam_result = DB::table('sm_student_take_online_exams')
                ->join('sm_online_exams', 'sm_online_exams.id', '=', 'online_exam_id')
                ->join('sm_subjects', 'sm_online_exams.subject_id', '=', 'sm_subjects.id')
                ->where('sm_student_take_online_exams.student_id', $record->student_id)
                ->where('sm_student_take_online_exams.school_id', $record->school_id)
                ->where('sm_online_exams.id', $exam_id)
                ->where('sm_online_exams.status', '=', 1)
                ->select(
                    'sm_online_exams.title as exam_name',
                    'sm_online_exams.id as exam_id',
                    'sm_subjects.subject_name',
                    'sm_student_take_online_exams.total_marks as obtained_marks',
                    'sm_online_exams.percentage as pass_mark_percentage',
                    'sm_student_take_online_exams.total_marks'
                )
                ->get();
            $gradeArray = [];
            foreach ($exam_result as $row) {

                $mark = floor($row->obtained_marks);
                $grades = DB::table('sm_marks_grades')
                    ->where('percent_from', '<=', $mark)
                    ->where('percent_upto', '>=', $mark)
                    ->select('grade_name')
                    ->first();
                $gradeArray[] = array(
                    "grade" => $grades->grade_name,
                    "exam_id" => $row->exam_id,
                    "total_marks" => $row->total_marks,
                    "subject_name" => $row->subject_name,
                    "obtained_marks" => $row->obtained_marks,
                    "pass_mark" => $row->pass_mark_percentage,
                    "exam_name" => $row->exam_name,
                );
            }

            $data['student_exams'] = $student_exams->toArray();
            $data['exam_result'] = $gradeArray;

            return ApiBaseMethod::sendResponse($data, null);
        }
    }
    public function saas_examResultApi(Request $request, $school_id, $user_id, $exam_id, $record_id)
    {
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $student_id = SmStudent::where('user_id', $user_id)->where('school_id', $school_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();
            $student_exams = DB::table('sm_online_exams')
                ->where('class_id', @$record->class_id)
                ->where('section_id', @$record->section_id)
                ->where('school_id', @$record->school_id)
                ->select('sm_online_exams.title as exam_name', 'sm_online_exams.id as exam_id')
                ->where('school_id', $school_id)->get();

            $exam_result = DB::table('sm_student_take_online_exams')
                ->join('sm_online_exams', 'sm_online_exams.id', '=', 'online_exam_id')
                ->join('sm_subjects', 'sm_online_exams.subject_id', '=', 'sm_subjects.id')
                ->where('sm_student_take_online_exams.student_id', @$record->student_id)
                ->where('sm_student_take_online_exams.school_id', @$record->school_id)
                ->where('sm_online_exams.id', $exam_id)
                ->where('sm_online_exams.status', '=', 1)
                ->select(
                    'sm_online_exams.title as exam_name',
                    'sm_online_exams.id as exam_id',
                    'sm_subjects.subject_name',
                    'sm_student_take_online_exams.total_marks as obtained_marks',
                    'sm_online_exams.percentage as pass_mark_percentage',
                    'sm_student_take_online_exams.total_marks'
                )
                ->where('sm_student_take_online_exams.school_id', $school_id)->get();
            $gradeArray = [];
            foreach ($exam_result as $row) {

                $mark = floor($row->obtained_marks);
                $grades = DB::table('sm_marks_grades')
                    ->where('percent_from', '<=', $mark)
                    ->where('percent_upto', '>=', $mark)
                    ->select('grade_name')
                    ->where('school_id', $school_id)->first();
                $gradeArray[] = array(
                    "grade" => $grades->grade_name,
                    "exam_id" => $row->exam_id,
                    "total_marks" => $row->total_marks,
                    "subject_name" => $row->subject_name,
                    "obtained_marks" => $row->obtained_marks,
                    "pass_mark" => $row->pass_mark_percentage,
                    "exam_name" => $row->exam_name,
                );
            }

            $data['student_exams'] = @$student_exams->toArray();
            $data['exam_result'] = $gradeArray;

            return ApiBaseMethod::sendResponse($data, null);
        }
    }
    public function studentOnlineExamApi(Request $request, $user_id, $record_id)
    {

        try {

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $data = [];

                $student_id = SmStudent::where('user_id', $user_id)->value('id');
                $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();
                $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
                    ->where('school_id', $record->school_id)->first();
                date_default_timezone_set($time_zone_setup->time_zone);
                $now = date('g:i:s');
                $today = date('Y-m-d');

                $online_exams = SmOnlineExam::where('active_status', 1)
                    ->where('academic_id', SmAcademicYear::API_ACADEMIC_YEAR($record->school_id))
                    ->where('status', 1)->where('class_id', $record->class_id)
                    ->where('section_id', $record->section_id)
                    ->where('school_id', $record->school_id)
                    ->get();

                foreach ($online_exams as $online_exam) {
                    $startTime = strtotime($online_exam->date . ' ' . $online_exam->start_time);
                    $endTime = strtotime($online_exam->date . ' ' . $online_exam->end_time);
                    $s = SmOnlineExam::find($online_exam->id);

                    $now = strtotime("now");
                    if ($startTime <= $now && $now <= $endTime) {
                        $s->is_running = 1;
                        $s->is_closed = 0;
                        $s->is_waiting = 0;
                    } elseif ($startTime >= $now && $now <= $endTime) {
                        $s->is_waiting = 1;
                        $s->is_running = 0;
                        $s->is_closed = 0;
                    } elseif ($now >= $endTime) {
                        $s->is_closed = 1;
                        $s->is_running = 0;
                        $s->is_waiting = 0;
                    }
                    $s->save();

                    Log::info($s);
                }

                $online_exams = SmOnlineExam::where('sm_online_exams.active_status', 1)
                    ->where('sm_online_exams.academic_id', SmAcademicYear::API_ACADEMIC_YEAR($record->school_id))
                    ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_online_exams.subject_id')

                    ->where('class_id', $record->class_id)
                    ->where('section_id', $record->section_id)
                    ->where('sm_online_exams.school_id', $record->school_id)
                    ->select('sm_online_exams.id as exam_id', 'sm_online_exams.title as exam_title', 'sm_subjects.subject_name', 'sm_online_exams.date', 'sm_online_exams.status as onlineExamStatus', 'sm_online_exams.is_taken as onlineExamTakeStatus', 'is_running', 'is_waiting', 'is_closed')
                    ->get();
                $examStatus = '0 = Pending , 1 Published';
                $examTakenStatus = '0 = Take Exam , 1 = Alreday Submitted';
                $data['online_exams'] = $online_exams->toArray();
                $data['online_exams_status'] = $examStatus;
                $data['onlineExamTakenStatus'] = $examTakenStatus;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }

    public function saas_studentOnlineExamApi(Request $request, $school_id, $user_id, $record_id)
    {

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            $student_id = SmStudent::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('user_id', $user_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();
            $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
                ->where('school_id', $record->school_id)->first();
            date_default_timezone_set($time_zone_setup->time_zone);
            $now = date('g:i:s');
            $today = date('Y-m-d');

            $online_exams = SmOnlineExam::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('active_status', 1)
                ->where('academic_id', SmAcademicYear::API_ACADEMIC_YEAR($record->school_id))
                ->where('status', 1)->where('class_id', $record->class_id)
                ->where('section_id', $record->section_id)
                ->where('school_id', $record->school_id)
                ->get();

            foreach ($online_exams as $online_exam) {
                $startTime = strtotime($online_exam->date . ' ' . $online_exam->start_time);
                $endTime = strtotime($online_exam->date . ' ' . $online_exam->end_time);
                $s = SmOnlineExam::find($online_exam->id);

                $now = strtotime("now");
                if ($startTime <= $now && $now <= $endTime) {
                    $s->is_running = 1;
                    $s->is_closed = 0;
                    $s->is_waiting = 0;
                } elseif ($startTime >= $now && $now <= $endTime) {
                    $s->is_waiting = 1;
                    $s->is_running = 0;
                    $s->is_closed = 0;
                } elseif ($now >= $endTime) {
                    $s->is_closed = 1;
                    $s->is_running = 0;
                    $s->is_waiting = 0;
                }
                $s->save();

                Log::info($s);
            }

            $online_exams = SmOnlineExam::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('sm_online_exams.active_status', 1)
                ->where('sm_online_exams.academic_id', SmAcademicYear::API_ACADEMIC_YEAR($record->school_id))
                ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_online_exams.subject_id')

                ->where('class_id', $record->class_id)
                ->where('section_id', $record->section_id)
                ->where('sm_online_exams.school_id', $record->school_id)
                ->select('sm_online_exams.id as exam_id', 'sm_online_exams.title as exam_title', 'sm_subjects.subject_name', 'sm_online_exams.date', 'sm_online_exams.status as onlineExamStatus', 'sm_online_exams.is_taken as onlineExamTakeStatus', 'is_running', 'is_waiting', 'is_closed')
                ->get();
            $examStatus = '0 = Pending , 1 Published';
            $examTakenStatus = '0 = Take Exam , 1 = Alreday Submitted';
            $data['online_exams'] = $online_exams->toArray();
            $data['online_exams_status'] = $examStatus;
            $data['onlineExamTakenStatus'] = $examTakenStatus;
            return ApiBaseMethod::sendResponse($data, null);
        }
    }
    public function chooseExamApi(Request $request, $user_id, $record_id)
    {
        if (ApiBaseMethod::checkUrl($request->fullUrl())) { 
            $student_id = SmStudent::where('user_id', $user_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();

            $student_exams = DB::table('sm_online_exams')
                ->where('class_id', $record->class_id)
                ->where('section_id', $record->section_id)
                ->where('school_id', $record->school_id)
                ->select('sm_online_exams.title as exam_name', 'id as exam_id')
                ->get();
            return ApiBaseMethod::sendResponse($student_exams, null);
        }
    }
    public function saas_chooseExamApi(Request $request, $school_id, $user_id, $record_id)
    {
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $student_id = SmStudent::where('user_id', $user_id)->value('id');
            $record = StudentRecord::where('student_id', $student_id)->where('id', $record_id)->first();

            $student_exams = DB::table('sm_online_exams')
                ->where('class_id', @$record->class_id)
                ->where('section_id', @$record->section_id)
                ->where('school_id', @$record->school_id)
                ->select('sm_online_exams.title as exam_name', 'id as exam_id')
                ->where('school_id', $school_id)
                ->get();
            return ApiBaseMethod::sendResponse($student_exams, null);
        }
    }
}
