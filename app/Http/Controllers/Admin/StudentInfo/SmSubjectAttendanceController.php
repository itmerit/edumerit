<?php

namespace App\Http\Controllers\Admin\StudentInfo;

use App\User;
use App\SmClass;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmBaseSetup;
use App\SmClassSection;
use App\SmNotification;
use App\SmAssignSubject;
use App\SmStudentCategory;
use App\SmStudentAttendance;
use App\SmSubjectAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FlutterAppNotification;
use Modules\University\Entities\UnSubjectAssignStudent;
use App\Http\Requests\Admin\StudentInfo\StudentSubjectWiseAttendanceStoreRequest;
use App\Http\Requests\Admin\StudentInfo\StudentSubjectWiseAttendancSearchRequest;
use App\Http\Requests\Admin\StudentInfo\StudentSubjectWiseAttendanceSearchRequest;
use App\Http\Requests\Admin\StudentInfo\subjectAttendanceAverageReportSearchRequest;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class SmSubjectAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function index(Request $request)
    {
        try{
            $classes = SmClass::get();
            return view('backEnd.studentInformation.subject_attendance', compact('classes'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function search(StudentSubjectWiseAttendancSearchRequest $request)
    {
        try {
            $data = [];
            $input['attendance_date']= $request->attendance_date;
            $input['class']= $request->class_id;
            $input['subject']= $request->subject_id;
            $input['section']= SmClassSection::where('class_id', $request->class_id)->first()->section_id;
<<<<<<< HEAD

=======
>>>>>>> 9fb0ca2 (select fix)

            $classes = SmClass::get();
            $sections = SmClassSection::with('sectionName')->where('class_id', $input['class'])->get();
            $subjects = SmAssignSubject::with('subject')->where('class_id', $input['class'])->where('section_id', $input['section'])
                ->get();

            $students = StudentRecord::with('studentDetail', 'studentDetail.DateSubjectWiseAttendances')->where('class_id', $input['class'])->where('section_id', $input['section'])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if (moduleStatusCheck('University')) {
                $data['un_semester_label_id'] = $request->un_semester_label_id;
                $interface = App::make(UnCommonRepositoryInterface::class);
                $data += $interface->searchInfo($request);
                $data += $interface->oldValueSelected($request);
                $assigned_students =  UnSubjectAssignStudent::where('un_subject_id',$request->un_subject_id)
                ->where('un_semester_label_id',$request->un_semester_label_id)
                ->get('student_record_id')->toArray();
                $students =  StudentRecord::whereIn('id',$assigned_students)->get();
            }

            if ($students->isEmpty()) {
                Toastr::error('No Result Found', 'Failed');
                return redirect('subject-wise-attendance');
            }

            $attendance_type= $students[0]['studentDetail']['DateSubjectWiseAttendances'] != null  ? $students[0]['studentDetail']['DateSubjectWiseAttendances']['attendance_type']:'';

            if (!moduleStatusCheck('University')) {
                $search_info['class_name'] = SmClass::find($request->class_id)->class_name;
                $search_info['section_name'] = SmSection::find($input['section'])->section_name;
                $search_info['subject_name'] = SmSubject::find($request->subject_id)->subject_name;
            }

            $search_info['date'] = $input['attendance_date'];

            if (generalSetting()->attendance_layout==1) {
                return view('backEnd.studentInformation.subject_attendance_list', compact('classes', 'subjects', 'sections', 'students', 'attendance_type', 'search_info', 'input'))->with($data);
            } else {
                return view('backEnd.studentInformation.subject_attendance_list2', compact('classes', 'subjects', 'sections', 'students', 'attendance_type', 'search_info', 'input'))->with($data);
            }


        }catch (\Exception $e) {
             ;
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function storeAttendance(StudentSubjectWiseAttendanceStoreRequest $request)
    {
        try {
            foreach ($request->attendance as $record_id => $student) {
                $attendance = SmSubjectAttendance::where('student_id', gv($student, 'student'))
                    ->where('subject_id', $request->subject)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->date)))
                    ->where('class_id', gv($student, 'class'))
                    ->where('section_id', gv($student, 'section'))
                    ->where('student_record_id', $record_id)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();

                if ($attendance != "")
                {
                    $attendance->delete();
                }

                $attendance = new SmSubjectAttendance();
                $attendance->student_record_id = $record_id;
                $attendance->subject_id = $request->subject;
                $attendance->student_id = gv($student, 'student');
                $attendance->class_id = gv($student, 'class');
                $attendance->section_id = gv($student, 'section');
                $attendance->attendance_type = gv($student, 'attendance_type');
                $attendance->behaviour_type = gv($student, 'behaviour_type');
                $attendance->grade = gv($student, 'grade');
                $attendance->notes = gv($student, 'note');
                $attendance->school_id = Auth::user()->school_id;
                $attendance->academic_id = getAcademicId();
                $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
                $r= $attendance->save();

                $messege = "";
                $date = dateConvert($attendance->attendance_date);

                if(gv($student, 'student')){

                    $student = SmStudent::find(gv($student, 'student'));
                    $subject = SmSubject::find($request->subject);
                    $subject_name = $subject->subject_name;
                    if($student){
                        if($attendance->attendance_type == "P"){
                            $messege = app('translator')->get('student.Your_teacher_has_marked_you_present_in_the_attendance_on_subject', ['date' => $date,'subject_name' => $subject_name]);

                        }
                        elseif($attendance->attendance_type == "L"){
                            $messege = app('translator')->get('student.Your_teacher_has_marked_you_late_in_the_attendance_on_subject', ['date' => $date,'subject_name' => $subject_name]);
                        }
                        elseif($attendance->attendance_type == "A"){
                            $messege = app('translator')->get('student.Your_teacher_has_marked_you_absent_in_the_attendance_on_subject', ['date' => $date,'subject_name' => $subject_name]);
                        }
                        elseif($attendance->attendance_type == "F"){
                            $messege = app('translator')->get('student.Your_teacher_has_marked_you_halfday_in_the_attendance_on_subject', ['date' => $date,'subject_name' => $subject_name]);
                        }

                        $notification = new SmNotification();
                        $notification->user_id = $student->user_id;
                        $notification->role_id = 2;
                        $notification->date = date('Y-m-d');
                        $notification->message = $messege ;
                        $notification->school_id = Auth::user()->school_id;
                        $notification->academic_id = getAcademicId();
                        $notification->save();
                        try{
                            if($student->user){
                                $title = app('translator')->get('student.attendance_notication');
                                Notification::send($student->user, new FlutterAppNotification($notification,$title));
                            }

                        }
                        catch (\Exception $e) {

                            Log::info($e->getMessage());
                        }



                        // for parent user
                        $parent = SmParent::find($student->parent_id);
                        if($parent){
                            if($attendance->attendance_type == "P"){
                                $messege = app('translator')->get('student.Your_child_is_marked_present_in_the_attendance_on_subject', ['date' => $date , 'student_name'=> $student->full_name."'s" ,'subject_name' => $subject_name ]);

                            }
                            elseif($attendance->attendance_type == "L"){
                                $messege = app('translator')->get('student.Your_child_is_marked_late_in_the_attendance_on_subject', ['date' => $date ,'student_name'=> $student->full_name."'s" , 'subject_name' => $subject_name]);
                            }
                            elseif($attendance->attendance_type == "A"){
                                $messege = app('translator')->get('student.Your_child_is_marked_absent_in_the_attendance_on_subject', ['date' => $date, 'student_name'=> $student->full_name."'s" , 'subject_name' => $subject_name]);
                            }
                            elseif($attendance->attendance_type == "F"){
                                $messege = app('translator')->get('student.Your_child_is_marked_halfday_in_the_attendance_on_subject', ['date' => $date, 'student_name'=> $student->full_name."'s" , 'subject_name' => $subject_name]);
                            }

                            $notification = new SmNotification();
                            $notification->user_id = $parent->user_id;
                            $notification->role_id = 3;
                            $notification->date = date('Y-m-d');
                            $notification->message = $messege;
                            $notification->school_id = Auth::user()->school_id;
                            $notification->academic_id = getAcademicId();
                            $notification->save();

                            try{
                                $user=User::find($notification->user_id);
                                if($user){
                                    $title = app('translator')->get('student.attendance_notication');
                                    Notification::send($user, new FlutterAppNotification($notification,$title));
                                }

                            }
                            catch (\Exception $e) {

                                Log::info($e->getMessage());
                            }
                        }
                    }


                }


            }
            Toastr::success('Operation successful', 'Success');
            return redirect('subject-wise-attendance');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function storeAttendanceSecond(Request $request)
    {

        try {
            foreach ($request->attendance as $record_id => $student) {

                $attendance_type = gv($student, 'attendance_type') ? gv($student, 'attendance_type') : 'A' ;
                $attendance = SmSubjectAttendance::where('student_id', gv($student, 'student'))
                    ->where('subject_id', $request->subject)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->where('class_id', gv($student, 'class'))
                    ->where('section_id', gv($student, 'section'))
                    ->where('student_record_id', $record_id)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if ($attendance !="") {
                    $attendance->delete();
                }

                $attendance = new SmSubjectAttendance();
                $attendance->student_record_id = $record_id;
                $attendance->subject_id = $request->subject;
                $attendance->student_id = gv($student, 'student');
                $attendance->class_id = gv($student, 'class');
                $attendance->section_id = gv($student, 'section');
                $attendance->attendance_type = $attendance_type;
                $attendance->notes = gv($student, 'note');
                $attendance->school_id = Auth::user()->school_id;
                $attendance->academic_id = getAcademicId();
                $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                $r= $attendance->save();
            }
            return response()->json('success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function subjectHolidayStore(Request $request)
    {
        $active_students = SmStudent::where('active_status', 1)
            ->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)
            ->get()->pluck('id')->toArray();
        $students = StudentRecord::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereIn('student_id', $active_students)
            ->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)
            ->get();

        if ($students->isEmpty()) {
            Toastr::error('No Result Found', 'Failed');
            return redirect('subject-wise-attendance');
        }
        if ($request->purpose == "mark") {
            foreach ($students as $record) {
                $attendance = SmSubjectAttendance::where('student_id', $record->student_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->where('class_id', $request->class_id)->where('section_id', $request->section_id)
                    ->where('student_record_id', $record->id)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if (!empty($attendance)) {
                    $attendance->delete();
                    $attendance = new SmSubjectAttendance();
                    $attendance->attendance_type= "H";
                    $attendance->notes= "Holiday";
                    $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                    $attendance->student_id = $record->student_id;
                    $attendance->subject_id = $request->subject_id;
                    $attendance->student_record_id = $record->id;
                    $attendance->class_id = $record->class_id;
                    $attendance->section_id = $record->section_id;
                    $attendance->academic_id = getAcademicId();
                    $attendance->school_id = Auth::user()->school_id;
                    $attendance->save();
                } else {
                    $attendance = new SmSubjectAttendance();
                    $attendance->attendance_type= "H";
                    $attendance->notes= "Holiday";
                    $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                    $attendance->student_id = $record->student_id;
                    $attendance->subject_id = $request->subject_id;

                    $attendance->student_record_id = $record->id;
                    $attendance->class_id = $record->class_id;
                    $attendance->section_id = $record->section_id;

                    $attendance->academic_id = getAcademicId();
                    $attendance->school_id = Auth::user()->school_id;
                    $attendance->save();
                }


                //notification

                $messege = "";
                $date = dateConvert($attendance->attendance_date);

                $student = SmStudent::find($record->student_id);
                $subject = SmSubject::find($request->subject_id);
                $subject_name = $subject->subject_name;

                if($student){
                    $messege = app('translator')->get('student.Your_teacher_has_marked_holiday_in_the_attendance_on_subject', ['date' => $date,'subject_name' => $subject_name]);

                    $notification = new SmNotification();
                    $notification->user_id = $student->user_id;
                    $notification->role_id = 2;
                    $notification->date = date('Y-m-d');
                    $notification->message = $messege ;
                    $notification->school_id = Auth::user()->school_id;
                    $notification->academic_id = getAcademicId();
                    $notification->save();
                    try{
                        if($student->user){
                            $title = app('translator')->get('student.attendance_notication');
                            Notification::send($student->user, new FlutterAppNotification($notification,$title));
                        }

                    }
                    catch (\Exception $e) {
                        Log::info($e->getMessage());
                    }



                    // for parent user
                    $parent = SmParent::find($student->parent_id);
                    if($parent){
                        $messege = app('translator')->get('student.Your_child_is_marked_holiday_in_the_attendance_on_subject', ['date' => $date , 'student_name'=> $student->full_name."'s" ,'subject_name' => $subject_name ]);

                        $notification = new SmNotification();
                        $notification->user_id = $parent->user_id;
                        $notification->role_id = 3;
                        $notification->date = date('Y-m-d');
                        $notification->message = $messege;
                        $notification->school_id = Auth::user()->school_id;
                        $notification->academic_id = getAcademicId();
                        $notification->save();

                        try{
                            $user=User::find($notification->user_id);
                            if($user){
                                $title = app('translator')->get('student.attendance_notication');
                                Notification::send($user, new FlutterAppNotification($notification,$title));
                            }

                        }
                        catch (\Exception $e) {
                            Log::info($e->getMessage());
                        }
                    }
                }

            }
        } elseif ($request->purpose == "unmark") {
            foreach ($students as $record) {
                $attendance = SmSubjectAttendance::where('student_id', $record->student_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->where('class_id', $request->class_id)->where('section_id', $request->section_id)
                    ->where('student_record_id', $record->id)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if (!empty($attendance)) {
                    $attendance->delete();
                }
            }
        }
        Toastr::success('Operation successful', 'Success');
        return redirect('subject-wise-attendance');
    }

    public function subjectAttendanceReport(Request $request)
    {
        try{

            $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();

            $types = SmStudentCategory::where('school_id',Auth::user()->school_id)->get();

            $genders = SmBaseSetup::where('active_status', '=', '1')
                ->where('base_group_id', '=', '1')
                ->where('school_id',Auth::user()->school_id)
                ->get();

            $records = [];
            return view('backEnd.studentInformation.subject_attendance_report_view', compact('records','classes', 'types', 'genders'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function subjectAttendanceReportSearch(StudentSubjectWiseAttendanceSearchRequest $request)
    {

        try{
            $year = $request->year;
            $month = $request->month;
            $class_id = $request->class;
            $section_id = $request->section;
            $assign_subjects = SmAssignSubject::where('class_id',$class_id)
                ->where('section_id',$section_id)
                ->first();

            if(!$assign_subjects){
                Toastr::warning('Subject Not Assign', 'Failed');
                return redirect()->back();
            }
            $subject_id = $assign_subjects->subject_id;
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();

            $query = StudentRecord::query()
                ->where('school_id',auth()->user()->school_id)
                ->where('academic_id',getAcademicId())
                ->where('is_promote',0)
                ->where(function ($query) use ($year, $month) {
                    $query->has('subjectAttendances')
                        ->orWheredoesntHave('subjectAttendances', function ($query) use ($year, $month) {
                            $query->where('attendance_date', 'like', $year . '-' . $month . '%')
                                ->where('academic_id', getAcademicId())
                                ->where('school_id',Auth::user()->school_id);
                        });
                    $query->has('student')
                        ->orWheredoesntHave('student', function ($query) {
                            $query->where('active_status', 1);
                        });
                });

            if ($class_id != "")
                $query->where('class_id', $class_id);

            if ($section_id != "")
                $query->where('section_id', $section_id);

            $records = [];
            if ($query->count()){
                $total_grand_present = 0;
                $total_late = 0;
                $total_absent = 0;
                $total_holiday = 0;
                $total_halfday = 0;
                foreach ($query->get() as $record) {
                    $total_attendance = 0;
                    $count_absent = 0;
                    $r = [
                        'full_name' => $record->student?->full_name,
                        'admission_no' => $record->student?->admission_no,
                        'p' => $record->subjectAttendances!=null?$record->subjectAttendances
                            ->filter(function ($r) use ($total_attendance, $total_grand_present) {
                                $total_attendance++;
                                $total_grand_present++;
                                return $r->attendance_type == 'P';
                            })->count():0,
                        'l' => $record->subjectAttendances!==null?$record->subjectAttendances
                            ->filter(function ($r) use ($total_attendance, $total_late) {
                                $total_attendance++;
                                $total_late++;
                                return $r->attendance_type == 'L';
                            })->count():0,
                        'a' => $record->subjectAttendances!==null?$record->subjectAttendances
                            ->filter(function ($r) use ($count_absent,$total_attendance,$total_absent) {
                                $count_absent++;
                                $total_attendance++;
                                $total_absent++;
                                return $r->attendance_type == 'A';
                            })->count():0,
                        'f' => $record->subjectAttendances!=null?$record->subjectAttendances
                            ->filter(function ($r) use ($total_attendance,$total_halfday) {
                                $total_attendance++;
                                $total_halfday++;
                                return $r->attendance_type == 'F';
                            })->count():0,
                        'h' => $record->subjectAttendances!==null?$record->subjectAttendances
                            ->filter(function ($r) use ($total_attendance,$total_holiday) {
                                $total_attendance++;
                                $total_holiday++;
                                return $r->attendance_type == 'H';
                            })->count():0,
                    ];
                    $total_present = $total_attendance - $count_absent;
                    $lines = [$total_present . '/' . $total_attendance];
                    if ($count_absent == 0) {
                        $lines[]= '100%';
                    } else {
                        $percentage = ($total_present / $total_attendance) * 100;
                        $lines[]= number_format((float) $percentage, 2, '.', '') . '%';
                    }

                    $r[] = $lines;
                    for ($i = 1; $i <= $days; $i++) {
                        $day = [];
                        $date_present = 0;
                        $date_absent = 0;
                        $date_total_class = 0;
                        foreach ($record->subjectAttendances as $value) {
                            if (strtotime($value->attendance_date) == strtotime("$year-$month-$i")) {
                                if ($value->attendance_type == 'P' || $value->attendance_type == 'F' || $value->attendance_type == 'L') {
                                    $date_present++;
                                } else {
                                    $date_absent++;
                                }
                                $date_total_class = $date_present + $date_absent;
                            }
                        }
                        if ($date_total_class != 0) {
                            $day[] = $date_present . '/' . $date_total_class;
                            foreach($record->subjectAttendances as $attendance){
                                if ($attendance->grade&&$attendance->subject!=null) {
                                    $day[] = $attendance->subject->subject_code.": ".($attendance->grade??0);
                                }
                            }
                        }
                        $r[] = $day;
                    }
                    $records[]=$r;
                }
            }else {
                Toastr::error('Student not assigned.', 'Failed');
            }

            return view('backEnd.studentInformation.subject_attendance_report_view', compact('classes', 'records', 'days', 'year', 'month', 'current_day', 'class_id', 'section_id','subject_id'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function subjectAttendanceAverageReport(Request $request)

    {

        try{

            $classes = SmClass::get();

            $types = SmStudentCategory::withoutGlobalScope(AcademicSchoolScope::class)->where('school_id',Auth::user()->school_id)->get();

            $genders = SmBaseSetup::where('base_group_id', '=', '1')->get();

            return view('backEnd.studentInformation.subject_attendance_report_average_view', compact('classes', 'types', 'genders'));

        }catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');

            return redirect()->back();

        }

    }
    public function subjectAttendanceAverageReportSearch(subjectAttendanceAverageReportSearchRequest $request)

    {
        try{

            $year = $request->year;

            $month = $request->month;

            $class_id = $request->class_id;

            $section_id = $request->section_id;

            $assign_subjects=SmAssignSubject::where('class_id', $class_id)->where('section_id', $section_id)->first();

            if(!$assign_subjects){

                Toastr::error('No Subject Assign ', 'Failed');

                return redirect()->back();
            }
            $subject_id = $assign_subjects->subject_id;

            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);

            $classes = SmClass::get();
            $activeStudentIds = SmStudentAttendanceController::activeStudent()->pluck('id')->toArray();
            $students = StudentRecord::where('class_id', $request->class)->where('section_id', $request->section)->whereIn('student_id', $activeStudentIds)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get()->sortBy('roll_no');

            $attendances = [];

            foreach ($students as $record) {

                $attendance = SmSubjectAttendance::where('sm_subject_attendances.student_id', $record->student_id)

                    //  ->join('student_records','student_records.student_id','=','sm_subject_attendances.student_id')

                    // // ->where('subject_id', $subject_id)

                    ->where('attendance_date', 'like', $year . '-' . $month . '%')
                    ->where('sm_subject_attendances.student_record_id', $record->id)
                    ->where('sm_subject_attendances.academic_id', getAcademicId())
                    ->where('sm_subject_attendances.school_id', Auth::user()->school_id)

                    ->get();

                if ($attendance) {

                    $attendances[] = $attendance;

                }

            }
            $selected['class_id']= $class_id;
            $selected['section_id']= $section_id;
            //   return $attendances;
            return view('backEnd.studentInformation.subject_attendance_report_average_view', compact('classes', 'attendances', 'days', 'year', 'month', 'current_day', 'class_id', 'section_id', 'subject_id', 'selected'));

        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');

            return redirect()->back();

        }

    }


    public function studentAttendanceReportPrint($class_id, $section_id, $month, $year)
    {
        try{
            $current_day = date('d');
            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $classes = SmClass::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $activeStudentIds = SmStudentAttendanceController::activeStudent()->pluck('id')->toArray();
            $students = StudentRecord::where('class_id', $class_id)->where('section_id', $section_id)->whereIn('student_id', $activeStudentIds)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $attendances = [];
            foreach ($students as $record) {
                $attendance = SmStudentAttendance::where('student_id', $record->student_id)->where('attendance_date', 'like', $year . '-' . $month . '%')->where('school_id',Auth::user()->school_id)
                    ->where('student_record_id', $record->id)
                    ->get();
                if (count($attendance) != 0) {
                    $attendances[] = $attendance;
                }
            }

            return view('backEnd.studentInformation.student_attendance_report', compact('classes', 'attendances', 'days', 'year', 'month', 'current_day', 'class_id', 'section_id'));

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }
    public function subjectAttendanceReportAveragePrint($class_id, $section_id, $month, $year){
        set_time_limit(2700);
        try{
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $activeStudentIds = SmStudentAttendanceController::activeStudent()->pluck('id')->toArray();
            $students = StudentRecord::where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->whereIn('student_id', $activeStudentIds)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $attendances = [];

            foreach ($students as $record) {
                $attendance = SmSubjectAttendance::where('sm_subject_attendances.student_id', $record->student_id)
                    // ->join('student_records','student_records.student_id','=','sm_subject_attendances.student_id')
                    ->where('sm_subject_attendances.student_record_id', $record->id)
                    ->where('attendance_date', 'like', $year . '-' . $month . '%')
                    ->where('sm_subject_attendances.academic_id', getAcademicId())
                    ->where('sm_subject_attendances.school_id',Auth::user()->school_id)
                    ->get();

                if ($attendance) {
                    $attendances[] = $attendance;
                }
            }

            return view('backEnd.studentInformation.student_subject_attendance',compact('attendances','days' , 'year'  , 'month','class_id'  ,'section_id'));

        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function subjectAttendanceReportPrint($class_id, $section_id, $month, $year)
    {
        set_time_limit(2700);
        try{
            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $query = StudentRecord::query()
                ->where('school_id',auth()->user()->school_id)
                ->where('academic_id',getAcademicId())
                ->where('is_promote',0)
                ->whereHas('subjectAttendances', function ($q) use ($year, $month) {
                    $q->where('attendance_date', 'like', $year . '-' . $month . '%')
                        ->where('academic_id', getAcademicId())
                        ->where('school_id',Auth::user()->school_id);
                })
                ->whereHas('student', function ($q)  {
                    $q->where('active_status', 1);
                });

            if ($class_id != "")
                $query->where('class_id', $class_id);

            if ($section_id != "")
                $query->where('section_id', $section_id);

            // xls
            if ($query->count()) {
                $hColumns = ["SL", "Name", "Admission No", "P", "L", "A", "F", "H", "%"];
                for ($i = 1; $i <= $days; $i++) {
                    $date = $year . '-' . $month . '-' . $i;
                    if (date('w', strtotime($date)) > 0) {
                        $hColumns[] = $i . '/' . date('D', strtotime($date));
                    }
                }

                $columns = [$hColumns];
                foreach ($query->get() as $record) {
                    $column = [];
                    $totalGrandPresent = 0;
                    $totalLate = 0;
                    $totalAbsent = 0;
                    $totalHoliday = 0;
                    $totalHalfDay = 0;
                    $totalAttendance = 0;
                    $countAbsent = 0;

                    $p = 0;
                    $l = 0;
                    $a = 0;
                    $f = 0;
                    $h = 0;
                    $column[] = $record->student?->full_name;
                    $column[] = $record->student?->admission_no;
                    foreach ($record->subjectAttendances as $attendance) {

                        if ($attendance->attendance_type == 'P') {
                            $p++;
                            $totalAttendance++;
                            $totalGrandPresent++;
                        }

                        if ($attendance->attendance_type == 'L') {
                            $l++;
                            $totalAttendance++;
                            $totalLate++;
                        }

                        if ($attendance->attendance_type == 'A') {
                            $a++;
                            $countAbsent++;
                            $totalAttendance++;
                            $totalAbsent++;
                        }

                        if ($attendance->attendance_type == 'F') {
                            $f++;
                            $totalAttendance++;
                            $totalHalfDay++;
                        }

                        if ($attendance->attendance_type == 'H') {
                            $h++;
                            $totalAttendance++;
                            $totalHoliday++;
                        }
                    }

                    $column[] = $p;
                    $column[] = $l;
                    $column[] = $a;
                    $column[] = $f;
                    $column[] = $h;

                    $totalPresent = $totalAttendance - $countAbsent;
                    $column[] = $totalPresent . '/' . $totalAttendance;
                    if ($countAbsent == 0) {
                        $column[] = '100%';
                    } else {
                        $percentage = ($totalPresent / $totalAttendance) * 100;
                        $column[] = number_format((float)$percentage, 2, '.', '') . '%';
                    }

                    for ($i = 1; $i <= $days; $i++) {
                        $date = $year . '-' . $month . '-' . $i;
                        if (date('w', strtotime($date)) > 0) {
                            $date = $year . '-' . $month . '-' . $i;
                            $datePresent = 0;
                            $dateAbsent = 0;
                            $dateTotalClass = 0;

                            foreach ($record->subjectAttendances as $attendance) {
                                if (strtotime($attendance->attendance_date) == strtotime($date)) {
                                    if ($attendance->attendance_type == 'P' || $attendance->attendance_type == 'F' || $attendance->attendance_type == 'L') {
                                        $datePresent++;
                                    } else {
                                        $dateAbsent++;
                                    }
                                    $dateTotalClass = $datePresent + $dateAbsent;
                                }
                            }

                            $last = "";
                            if ($dateTotalClass != 0) {
                                $last = $datePresent . '/' . $dateTotalClass . "\n";
                                foreach ($record->subjectAttendances as $attendance) {
                                    $last .= $attendance?->subject?->subject_code . ": " . ($attendance->grade ?? 0) . "\n";
                                }
                            }
                            $column[] = $last;
                        }
                    }
                    $columns[] = $column;
                }

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sHeaders = $this->getSheetHeaders($hColumns);

                foreach ($columns as $k => $col) {
                    for ($i = 0; $i < count($sHeaders); $i++) {
                        $sheet->setCellValue($sHeaders[$i] . ($k + 1), $col[$i]);
                        $sheet->getColumnDimension($sHeaders[$i])->setAutoSize(true);
                    }
                }

                $writer = new Xls($spreadsheet);
                $path = storage_path('app/report.xls');
                $writer->save($path);
                return response()->download($path)->deleteFileAfterSend();
            } else {
                Toastr::success("There aren't anything.", "Success");
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    private function getSheetHeaders($columns) {
        $letters = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
        $cc = count($columns);
        $lc = count($letters);

        if ($cc>$lc) {
            $m = (int)($cc/$lc);
            $xtColumns = [];
            $resultColumns = $letters;
            for ($i=0;$i<$m;$i++){
                $fj = $cc%$lc;
                if ($fj > 0){
                    for ($j=0;$j<$fj;$j++){
                        $xtColumns[] = $letters[$m-1].$letters[$j];
                    }
                }
            }
            return array_merge($resultColumns, $xtColumns);
        }

        return $letters;
    }
}
