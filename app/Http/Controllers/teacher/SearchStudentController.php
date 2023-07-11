<?php

namespace App\Http\Controllers\teacher;

use App\Role;
use App\SmClass;
use App\SmGeneralSettings;
use App\SmStaff;
use App\SmStudent;
use App\SmWeekend;
use App\YearCheck;
use App\SmHomework;
use App\SmClassTime;
use App\ApiBaseMethod;
use App\SmLeaveRequest;
use App\SmNotification;
use App\SmAssignSubject;
use App\SmStaffAttendence;
use Illuminate\Http\Request;
use App\SmTeacherUploadContent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;

class SearchStudentController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function viewTeacherRoutine()
    {
        try {
            // $assinged_subjects = SmAssignSubject::where('active_status', 1)->where('teacher_id', 4)->distinct()->get(['subject_id']);

            // $class_routines = [];
            // foreach($assinged_subjects as $assinged_subject){
            // 	$class_routines[] = SmClassRoutine::where('subject_id', $assinged_subject->subject_id)->first();
            // }
            $user = Auth::user();

            $class_times = SmClassTime::where('school_id',Auth::user()->school_id)->get();
            $teacher_id = $user->staff->id;

            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();
            $teachers = SmStaff::select('id', 'full_name')->where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();

            return view('backEnd.teacherPanel.view_class_routine', compact('class_times', 'teacher_id', 'sm_weekends', 'teachers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function searchStudent(Request $request)
    {

        try {
            $class_id = $request->class;
            $section_id = $request->section;
            $name = $request->name;
            $roll_no = $request->roll_no;
            $students = '';
            $msg = '';

            if (!empty($request->class) && !empty($request->section)) {
                $students = DB::table('sm_students')
                    ->select('sm_students.id', 'student_photo', 'full_name', 'roll_no', 'class_name', 'section_name', 'user_id')
                    ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
                    ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                    ->where('sm_students.class_id', $request->class)
                    ->where('sm_students.section_id', $request->section)
                    ->where('sm_students.school_id',Auth::user()->school_id)->get();
                $msg = "Student Found";
            } elseif (!empty($request->class)) {
                $students = DB::table('sm_students')
                    ->select('sm_students.id', 'student_photo', 'full_name', 'roll_no', 'class_name', 'section_name', 'user_id')
                    ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
                    ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                    ->where('sm_students.class_id', $class_id)
                    // ->where('section_id',$section_id)
                    ->where('sm_students.school_id',Auth::user()->school_id)->get();
                $msg = "Student Found";
            } elseif ($request->name != "") {
                $students = DB::table('sm_students')
                    ->select('sm_students.id', 'student_photo', 'full_name', 'roll_no', 'class_name', 'section_name', 'user_id')
                    ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
                    ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                    ->where('full_name', 'like', '%' . $request->name . '%')
                    ->first();
                $msg = "Student Found";
            } elseif ($request->roll_no != "") {
                $students = DB::table('sm_students')
                    ->select('sm_students.id', 'student_photo', 'full_name', 'roll_no', 'class_name', 'section_name', 'user_id')
                    ->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')
                    ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                    ->where('roll_no', 'like', '%' . $request->roll_no . '%')
                    ->first();
                $msg = "Student Found";
            } else {

                $msg = "Student Not Found";
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students;

                return ApiBaseMethod::sendResponse($data, $msg);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function myRoutine(Request $request, $id)
    {

        try {
            $teacher = DB::table('sm_staffs')
                ->where('user_id', '=', $id)
                ->first();
            $teacher_id = $teacher->id;

            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();
            $class_times = SmClassTime::where('type', 'class')->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $weekenD = SmWeekend::where('school_id', Auth::user()->school_id)->get();
                foreach ($weekenD as $row) {
                    $data[$row->name] = DB::table('sm_class_routine_updates')
                        ->select('class_id', 'class_name', 'section_id', 'section_name', 'sm_class_times.period', 'sm_class_times.start_time', 'sm_class_times.end_time', 'sm_subjects.subject_name', 'sm_class_rooms.room_no')
                        ->join('sm_classes', 'sm_classes.id', '=', 'sm_class_routine_updates.class_id')
                        ->join('sm_sections', 'sm_sections.id', '=', 'sm_class_routine_updates.section_id')
                        ->join('sm_class_times', 'sm_class_times.id', '=', 'sm_class_routine_updates.class_period_id')
                        ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_class_routine_updates.subject_id')
                        ->join('sm_class_rooms', 'sm_class_rooms.id', '=', 'sm_class_routine_updates.room_id')

                        ->where([
                            ['sm_class_routine_updates.teacher_id', $teacher_id], ['sm_class_routine_updates.day', $row->id],
                        ])->where('sm_sections.school_id',Auth::user()->school_id)->get();
                }

                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function sectionRoutine(Request $request, $id, $class, $section)
    {
        try {
            $teacher = DB::table('sm_staffs')
                ->where('user_id', '=', $id)
                ->first();
            $teacher_id = $teacher->id;

            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();
            $class_times = SmClassTime::where('type', 'class')->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $weekenD = SmWeekend::where('school_id', Auth::user()->school_id)->get();
                foreach ($weekenD as $row) {
                    $data[$row->name] = DB::table('sm_class_routine_updates')
                        ->select('sm_class_times.period', 'sm_class_times.start_time', 'sm_class_times.end_time', 'sm_subjects.subject_name', 'sm_class_rooms.room_no')
                        ->join('sm_classes', 'sm_classes.id', '=', 'sm_class_routine_updates.class_id')
                        ->join('sm_sections', 'sm_sections.id', '=', 'sm_class_routine_updates.section_id')
                        ->join('sm_class_times', 'sm_class_times.id', '=', 'sm_class_routine_updates.class_period_id')
                        ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_class_routine_updates.subject_id')
                        ->join('sm_class_rooms', 'sm_class_rooms.id', '=', 'sm_class_routine_updates.room_id')

                        ->where([
                            ['sm_class_routine_updates.teacher_id', $teacher_id],
                            ['sm_class_routine_updates.class_id', $class],
                            ['sm_class_routine_updates.section_id', $section],
                            ['sm_class_routine_updates.day', $row->id],
                        ])->where('sm_sections.school_id',Auth::user()->school_id)->get();
                }

                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function classSection(Request $request, $id)
    {
        try {
            $teacher = DB::table('sm_staffs')
                ->where('user_id', '=', $id)
                ->first();
            $teacher_id = $teacher->id;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $teacher_classes = DB::table('sm_assign_subjects')
                    ->join('sm_classes', 'sm_classes.id', '=', 'sm_assign_subjects.class_id')
                    ->distinct('class_id')

                    ->where('teacher_id', $teacher_id)->get();
                foreach ($teacher_classes as $class) {
                    $data[$class->class_name] = DB::table('sm_assign_subjects')
                        ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_assign_subjects.subject_id')
                        ->join('sm_sections', 'sm_sections.id', '=', 'sm_assign_subjects.section_id')
                        ->select('section_name', 'subject_name')
                        ->distinct('section_id')
                        ->where([
                            ['sm_assign_subjects.class_id', $class->id],
                        ])->get();
                }

                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    //Some Changes
    public function subjectsName(Request $request, $id)
    {

        try {
            $teacher = DB::table('sm_staffs')
                ->where('user_id', '=', $id)
                ->first();
            $teacher_id = $teacher->id;

            $subjectsName = DB::table('sm_assign_subjects')
                ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_assign_subjects.subject_id')
                ->select('subject_id', 'subject_name', 'subject_code', 'subject_type')
                ->where('sm_assign_subjects.active_status', 1)
                ->where('teacher_id', $teacher_id)
                ->distinct('subject_id')
                ->where('sm_assign_subjects.school_id',Auth::user()->school_id)->get();
            $subject_type = 'T=Theory, P=Practical';
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['subjectsName'] = $subjectsName->toArray();
                $data['subject_type'] = $subject_type;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function addHomework(Request $request)
    {
        $request->validate([
            'class' => "required",
            'section' => "required",
            'subject' => "required",
            'assign_date' => "required",
            'submission_date' => "required",
            'description' => "required",
            'marks' => "required"
        ]);

        try {
            $fileName = "";
            if ($request->file('homework_file') != "") {


                $file = $request->file('homework_file');
                $fileName = $request->teacher_id . time() . "." . $file->getClientOriginalExtension();
                //$fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/homework/', $fileName);
                $fileName = 'public/uploads/homework/' . $fileName;
            }

            $homeworks = new SmHomework;
            $homeworks->class_id = $request->class;
            $homeworks->section_id = $request->section;
            $homeworks->subject_id = $request->subject;
            $homeworks->marks = $request->marks;
            $homeworks->created_by = $request->teacher_id;
            $homeworks->homework_date = $request->assign_date;
            $homeworks->submission_date = $request->submission_date;
            //$homeworks->marks = $request->marks;
            $homeworks->description = $request->description;
            $homeworks->academic_id = getAcademicId();
            if ($fileName != "") {
                $homeworks->file = $fileName;
            }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $results = $homeworks->save();

                return ApiBaseMethod::sendResponse($results, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function homeworkList2(Request $request, $id)
    {
        try {
            $teacher = DB::table('sm_staffs')
                ->where('user_id', '=', $id)
                ->first();
            $teacher_id = $teacher->id;

            $homeworkLists = SmHomework::where('sm_homeworks.created_by', '=', $teacher_id)
                ->join('sm_classes', 'sm_homeworks.class_id', '=', 'sm_classes.id')
                ->join('sm_sections', 'sm_homeworks.section_id', '=', 'sm_sections.id')
                ->join('sm_subjects', 'sm_homeworks.subject_id', '=', 'sm_subjects.id')
                ->select('homework_date', 'submission_date', 'evaluation_date', 'file', 'sm_homeworks.marks', 'description', 'subject_name', 'class_name', 'section_name')
                ->where('sm_homeworks.school_id',Auth::user()->school_id)->get();


            $classes = SmClass::where('active_status', '=', '1')->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];

                return ApiBaseMethod::sendResponse($homeworkLists, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function homeworkList(Request $request, $id)
    {
        try {
            $teacher = SmStaff::where('user_id', '=', $id)->first();
            $teacher_id = $teacher->id;


            $subject_list = SmAssignSubject::where('teacher_id', '=', $teacher_id)->where('school_id',Auth::user()->school_id)->get();

            $i = 0;
            foreach ($subject_list as $subject) {
                $homework_subject_list[$subject->subject->subject_name] = $subject->subject->subject_name;
                $allList[$subject->subject->subject_name] = DB::table('sm_homeworks')
                    ->leftjoin('sm_subjects', 'sm_subjects.id', '=', 'sm_homeworks.subject_id')
                    ->where('sm_homeworks.created_by', $teacher_id)
                    ->where('subject_id', $subject->subject_id)->get()->toArray();;
            }

            foreach ($allList as $single) {
                foreach ($single as $singleHw) {
                    $std_homework = DB::table('sm_homework_students')
                        ->select('homework_id', 'complete_status')
                        ->where('homework_id', '=', $singleHw->id)
                        ->where('complete_status', 'C')
                        ->first();

                    $d['homework_id'] = $singleHw->id;
                    $d['description'] = $singleHw->description;
                    $d['subject_name'] = $singleHw->subject_name;
                    $d['homework_date'] = $singleHw->homework_date;
                    $d['submission_date'] = $singleHw->submission_date;
                    $d['evaluation_date'] = $singleHw->evaluation_date;
                    $d['file'] = $singleHw->file;
                    $d['marks'] = $singleHw->marks;

                    if (!empty($std_homework)) {
                        $d['status'] = 'C';
                    } else {
                        $d['status'] = 'I';
                    }
                    $kijanidibo[] = $d;
                }
            }
            $data = [];
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = $kijanidibo;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function teacherMyAttendanceSearchAPI(Request $request, $id = null)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'month' => "required",
            'year' => "required",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $teacher = SmStaff::where('user_id', $id)->first();

            $year = $request->year;
            $month = $request->month;
            if ($month < 10) {
                $month = '0' . $month;
            }
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $month, $request->year);
            $days2 = cal_days_in_month(CAL_GREGORIAN, $month - 1, $request->year);
            $previous_month = $month - 1;
            $previous_date = $year . '-' . $previous_month . '-' . $days2;
            $previousMonthDetails['date'] = $previous_date;
            $previousMonthDetails['day'] = $days2;
            $previousMonthDetails['week_name'] = date('D', strtotime($previous_date));

            $attendances = SmStaffAttendence::where('student_id', $teacher->id)
                ->where('attendance_date', 'like', '%' . $request->year . '-' . $month . '%')
                ->select('attendance_type', 'attendance_date')
                ->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['attendances'] = $attendances;
                $data['previousMonthDetails'] = $previousMonthDetails;
                $data['days'] = $days;
                $data['year'] = $year;
                $data['month'] = $month;
                $data['current_day'] = $current_day;
                $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';
                return ApiBaseMethod::sendResponse($data, null);
            }
            //Test
            //return view('backEnd.studentPanel.student_attendance', compact('attendances', 'days', 'year', 'month', 'current_day'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function applyLeave(Request $request)
    {


        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'apply_date' => "required",
                'leave_type' => "required",
                'leave_from' => 'required|before_or_equal:leave_to',
                'leave_to' => "required",
                'teacher_id' => "required",
                'reason' => "required",
                'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",

            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        try {
            $fileName = "";
            if ($request->file('attach_file') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('attach_file');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('attach_file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/leave_request/', $fileName);
                $fileName = 'public/uploads/leave_request/' . $fileName;
            }

            $apply_leave = new SmLeaveRequest();
            $apply_leave->staff_id = $request->input('teacher_id');
            $apply_leave->role_id = 4;
            $apply_leave->apply_date = date('Y-m-d');
            $apply_leave->leave_define_id = $request->input('leave_type');
            $apply_leave->leave_from = $request->input('leave_from');
            $apply_leave->leave_to = $request->input('leave_to');
            $apply_leave->approve_status = 'P';
            $apply_leave->reason = $request->input('reason');
            $apply_leave->academic_id = getAcademicId();
            if ($fileName != "") {
                $apply_leave->file = $fileName;
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $result = $apply_leave->save();

                return ApiBaseMethod::sendResponse($result, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function staffLeaveList(Request $request, $id)
    {
        try {
            $teacher = SmStaff::where('user_id', '=', $id)->first();
            $teacher_id = $teacher->id;

            $leave_list = SmLeaveRequest::where('staff_id', '=', $teacher_id)
                ->join('sm_leave_defines', 'sm_leave_defines.id', '=', 'sm_leave_requests.leave_define_id')
                ->join('sm_leave_types', 'sm_leave_types.id', '=', 'sm_leave_defines.type_id')
                ->where('sm_leave_defines.school_id',Auth::user()->school_id)->get();
            $status = 'P for Pending, A for Approve, R for reject';
            $data = [];
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['leave_list'] = $leave_list->toArray();
                $data['status'] = $status;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function leaveTypeList(Request $request)
    {

        try {
            //return "Api URL";
            $leave_type = DB::table('sm_leave_defines')
                ->where('role_id', 4)
                ->join('sm_leave_types', 'sm_leave_types.id', '=', 'sm_leave_defines.type_id')
                ->where('sm_leave_defines.active_status', 1)
                ->select('sm_leave_types.id', 'type', 'total_days')
                ->distinct('sm_leave_defines.type_id')
                ->where('sm_leave_defines.school_id',Auth::user()->school_id)->get();

            //return $leave_type;
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($leave_type, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    // public function contentType(){

    // 	$content_type='as assignment, st study material, sy sullabus, ot others download';
    // 	return $content_type;
    // }
    public function uploadContent(Request $request)
    {


        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'content_title' => "required",
                'content_type' => "required",
                'upload_date' => "required",
                'description' => "required",
                'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]);
        }
        //as assignment, st study material, sy sullabus, ot others download

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        try {
            if (empty($request->input('available_for'))) {

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Validation Error.', 'Content Receiver not selected');
                }
            }
            $fileName = "";
            if ($request->file('attach_file') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('attach_file');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('attach_file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/upload_contents/', $fileName);
                $fileName = 'public/uploads/upload_contents/' . $fileName;
            }

            $uploadContents = new SmTeacherUploadContent();
            $uploadContents->content_title = $request->input('content_title');
            $uploadContents->content_type = $request->input('content_type');

            if ($request->input('available_for') == 'admin') {
                $uploadContents->available_for_admin = 1;
            } elseif ($request->input('available_for') == 'student') {
                if (!empty($request->input('all_classes'))) {
                    $uploadContents->available_for_all_classes = 1;
                } else {
                    $uploadContents->class = $request->input('class');
                    $uploadContents->section = $request->input('section');
                }
            }
           
            $uploadContents->upload_date = date('Y-m-d', strtotime($request->input('upload_date')));
            $uploadContents->description = $request->input('description');
            $uploadContents->upload_file = $fileName;
            $uploadContents->created_by = $request->input('created_by');
            $uploadContents->academic_id = getAcademicId();
            $results = $uploadContents->save();

            if ($request->input('content_type') == 'as') {
                $purpose = 'assignment';
            } elseif ($request->input('content_type') == 'st') {
                $purpose = 'Study Material';
            } elseif ($request->input('content_type') == 'sy') {
                $purpose = 'Syllabus';
            } elseif ($request->input('content_type') == 'ot') {
                $purpose = 'Others Download';
            }
            // foreach ($request->input('available_for') as $value) {
            if ($request->input('available_for') == 'admin') {
                $roles = InfixRole::where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 3)->where('id', '!=', 9)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();

                foreach ($roles as $role) {
                    $staffs = SmStaff::where('role_id', $role->id)->where('school_id',Auth::user()->school_id)->get();
                    foreach ($staffs as $staff) {
                        $notification = new SmNotification;
                        $notification->user_id = $staff->user_id;
                        $notification->role_id = $role->id;
                        $notification->date = date('Y-m-d');
                        $notification->message = $purpose . ' updated';
                        $notification->school_id = Auth::user()->school_id;
                        $notification->academic_id = getAcademicId();
                        $notification->save();
                    }
                }
            }
            if ($request->input('available_for') == 'student') {
                if (!empty($request->input('all_classes'))) {
                    $students = SmStudent::select('id')->where('school_id',Auth::user()->school_id)->get();
                    foreach ($students as $student) {
                        $notification = new SmNotification;
                        $notification->user_id = $student->user_id;
                        $notification->role_id = 2;
                        $notification->date = date('Y-m-d');
                        $notification->school_id = Auth::user()->school_id;
                        $notification->academic_id = getAcademicId();
                        $notification->save();
                    }
                } else {
                    $students = SmStudent::select('id')->where('class_id', $request->input('class'))->where('section_id', $request->input('section'))->where('school_id',Auth::user()->school_id)->get();
                    foreach ($students as $student) {
                        $notification = new SmNotification;
                        $notification->user_id = $student->user_id;
                        $notification->role_id = 2;
                        $notification->date = date('Y-m-d');
                        $notification->message = $purpose . ' updated';
                        $notification->school_id = Auth::user()->school_id;
                        $notification->academic_id = getAcademicId();
                        $notification->save();
                    }
                }
            }
            // }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $data = '';

                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function contentList(Request $request)
    {

        try {
            $content_list = DB::table('sm_teacher_upload_contents')
                ->where('available_for_admin', '<>', 0)
                ->where('school_id',Auth::user()->school_id)->get();
            $type = "as assignment, st study material, sy sullabus, ot others download";
            $data = [];
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['content_list'] = $content_list->toArray();
                $data['type'] = $type;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteContent(Request $request, $id)
    {
        try {
            $content = DB::table('sm_teacher_upload_contents')->where('id', $id)->delete();
            //$res=User::where('id',$id)->delete();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = '';
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
