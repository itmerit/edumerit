<?php

namespace App\Http\Controllers\Admin\StudentInfo;

use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\SmClassSection;
use App\SmNotification;
use App\SmStudentAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\StudentAttendanceBulk;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Notification;
use App\Imports\StudentAttendanceImport;
use App\Notifications\FlutterAppNotification;
use App\Http\Requests\Admin\StudentInfo\SmStudentAttendanceSearchRequest;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;
use Modules\University\Imports\StudentAttendanceImport as UniversityStudentAttendanceImport;

class SmStudentAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function index(Request $request)
    {
        try {
            if (teacherAccess()) {
                 $teacher_info= SmStaff::where('user_id', auth()->user()->id)->first();
                 $classes = $teacher_info->classes;
            } else {
                 $classes = SmClass::get();
            }
            return view('backEnd.studentInformation.student_attendance', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function studentSearch(SmStudentAttendanceSearchRequest $request)
    {


        try {
            $date = $request->attendance_date;
            if (teacherAccess()) {
                $teacher_info = SmStaff::where('user_id', auth()->user()->id)->first();
                $classes = $teacher_info->classes;
            } else {
                $classes = SmClass::get();
            }

            $students = StudentRecord::with('studentDetail', 'studentDetail.DateWiseAttendances')
            ->when($request->class_id, function ($query) use ($request) {
                $query->where('class_id', $request->class_id);
            })
            ->whereHas('studentDetail', function ($q)  {
                $q->where('active_status', 1);
            })

            ->when($request->section_id, function ($query) use ($request) {
                $query->where('section_id', $request->section_id);
            })->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)
            ->get()->sortBy('roll_no');

            if (moduleStatusCheck('University')) {
                $model = StudentRecord::query();
                $students = universityFilter($model, $request)
                ->with('studentDetail', 'studentDetail.DateWiseAttendances')
                ->get()->sortBy('roll_no');
            }

            if ($students->isEmpty()) {
                Toastr::error('No Result Found', 'Failed');
                return redirect('student-attendance');
            }


            $attendance_type = $students[0]['studentDetail']['DateWiseAttendances'] != null ? $students[0]['studentDetail']['DateWiseAttendances']['attendance_type'] : '';

            $class_id = $request->class_id;
            $section_id = $request->section_id;
            $selected['class_id']=$request->class_id;
            $selected['section_id']=$request->section_id;
           if (moduleStatusCheck('University')) {
                $interface = App::make(UnCommonRepositoryInterface::class);
                $search_info = $interface->searchInfo($request);
                $search_info += $interface->oldValueSelected($request);
            } else {
                $search_info['class_name'] = SmClass::find($request->class_id)->class_name;
                $search_info['section_name'] =  SmSection::find($request->section_id)->section_name;
            }
            $search_info['date'] = $request->attendance_date;
            $sections = SmClassSection::with('sectionName')->where('class_id', $request->class_id)->get();
            
            return view('backEnd.studentInformation.student_attendance', compact('classes', 'sections', 'class_id', 'section_id', 'date', 'students', 'attendance_type', 'search_info', 'selected'))->with($search_info);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function studentAttendanceStore(Request $request)
    {
        try {

            foreach ($request->attendance as $record_id => $student) {
                if (moduleStatusCheck('University')) {
                    $attendance = SmStudentAttendance::where('student_id', gv($student, 'student'))
                    ->where('attendance_date', date('Y-m-d', strtotime($request->date)))
                    ->when(gv($student, 'un_session_id'), function ($q) use ($student) {
                        $q->where('un_session_id', gv($student, 'un_session_id'));
                    })
                    ->when(gv($student, 'un_faculty_id'), function ($q) use ($student) {
                        $q->where('un_faculty_id', gv($student, 'un_faculty_id'));
                    })
                    ->when(gv($student, 'un_department_id'), function ($q) use ($student) {
                        $q->where('un_department_id', gv($student, 'un_department_id'));
                    })
                    ->when(gv($student, 'un_semester_id'), function ($q) use ($student) {
                        $q->where('un_academic_id', gv($student, 'un_academic_id'));
                    })
                    ->when(gv($student, 'un_semester_id'), function ($q) use ($student) {
                        $q->where('un_semester_id', gv($student, 'un_semester_id'));
                    })
                    ->when(gv($student, 'un_semester_label_id'), function ($q) use ($student) {
                        $q->where('un_semester_label_id', gv($student, 'un_semester_label_id'));
                    })
                    ->when(gv($student, 'un_section_id'), function ($q) use ($student) {
                        $q->where('un_section_id', gv($student, 'un_section_id'));
                    })

                    ->where('student_record_id', $record_id)
                    ->where('school_id', Auth::user()->school_id)->first();
                } else {
                    $attendance = SmStudentAttendance::where('student_id', gv($student, 'student'))
                    ->where('attendance_date', date('Y-m-d', strtotime($request->date)))

                    ->when(!moduleStatusCheck('University'), function ($query) use ($student) {
                        $query->where('class_id', gv($student, 'class'));
                    })->when(!moduleStatusCheck('University'), function ($query) use ($student) {
                        $query->where('section_id', gv($student, 'section'));
                    })
                    ->where('student_record_id', $record_id)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)->first();
                }
                if ($attendance) {
                    $attendance->delete();
                }


                $attendance = new SmStudentAttendance();
                $attendance->student_record_id = $record_id;
                $attendance->student_id = gv($student, 'student');
                $attendance->class_id = gv($student, 'class');
                $attendance->section_id = gv($student, 'section');
                if (isset($request->mark_holiday)) {
                    $attendance->attendance_type = "H";
                } else {
                    $attendance->attendance_type = gv($student, 'attendance_type');
                    $attendance->notes = gv($student, 'note');
                }
                $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
                $attendance->school_id = Auth::user()->school_id;
                $attendance->academic_id = getAcademicId();
                if (moduleStatusCheck('University')) {
                    $attendance->un_academic_id = gv($student, 'un_academic_id');
                    $attendance->un_session_id = gv($student, 'un_session_id');
                    $attendance->un_department_id = gv($student, 'un_department_id');
                    $attendance->un_faculty_id = gv($student, 'un_faculty_id');
                    $attendance->un_semester_id = gv($student, 'un_semester_id');
                    $attendance->un_semester_label_id =  gv($student, 'un_semester_label_id');
                    $attendance->un_section_id =  gv($student, 'un_section_id');
                }
                $attendance->save();

                $studentInfo = StudentRecord::find($record_id);
                $compact['attendance_date'] = date('Y-m-d', strtotime($request->date));
                if (gv($student, 'attendance_type') == "P") {
                    $compact['user_email'] = $studentInfo->studentDetail->email;
                    $compact['student_name'] = $studentInfo->studentDetail->full_name;
                    @send_sms($studentInfo->studentDetail->mobile, 'student_attendance', $compact);

                    $compact['user_email'] = $studentInfo->studentDetail->parents->guardians_email;
                    $compact['parent_name'] = $studentInfo->studentDetail->parents->guardians_name;
                    @send_sms($studentInfo->studentDetail->parents->guardians_mobile, 'student_attendance_for_parent', $compact);
                } elseif (gv($student, 'attendance_type') == "L") {
                    $compact['user_email'] = $studentInfo->studentDetail->email;
                    $compact['student_name'] = $studentInfo->studentDetail->full_name;
                    @send_sms($studentInfo->studentDetail->mobile, 'student_late', $compact);

                    $compact['user_email'] = $studentInfo->studentDetail->parents->guardians_email;
                    $compact['parent_name'] = $studentInfo->studentDetail->parents->guardians_name;
                    @send_sms($studentInfo->studentDetail->parents->guardians_mobile, 'student_late_for_parent', $compact);
                } elseif (gv($student, 'attendance_type') == "A") {
                    $compact['user_email'] = $studentInfo->studentDetail->email;
                    $compact['student_name'] = $studentInfo->studentDetail->full_name;
                    @send_sms($studentInfo->studentDetail->mobile, 'student_absent', $compact);

                    $compact['user_email'] = $studentInfo->studentDetail->parents->guardians_email;
                    $compact['parent_name'] = $studentInfo->studentDetail->parents->guardians_name;
                    @send_sms($studentInfo->studentDetail->parents->guardians_mobile, 'student_absent_for_parent', $compact);
                }
                // futter notification & normal
                $messege = "";
                $date = dateConvert($attendance->attendance_date);
                if(gv($student, 'student')){
                    $student_detail = SmStudent::find(gv($student, 'student'));
                    if($student){
                        if($attendance->attendance_type == "P"){
                            $messege = app('translator')->get('student.Your_teacher_has_marked_you_present_in_the_attendance_on ', ['date' => $date]);
                            
                        }
                        elseif($attendance->attendance_type == "L"){
                            $messege = app('translator')->get('student.Your_teacher_has_marked_you_late_in_the_attendance_on ', ['date' => $date]);
                        }
                        elseif($attendance->attendance_type == "A"){
                            $messege = app('translator')->get('student.Your_teacher_has_marked_you_absent_in_the_attendance_on ', ['date' => $date]);
                        }
                        elseif($attendance->attendance_type == "F"){
                            $messege = app('translator')->get('student.Your_teacher_has_marked_you_halfday_in_the_attendance_on ', ['date' => $date]);
                        }

                        $notification = new SmNotification();
                        $notification->user_id = $student_detail->user_id;
                        $notification->role_id = 2;
                        $notification->date = date('Y-m-d');
                        $notification->message = $messege;
                        $notification->school_id = Auth::user()->school_id;
                        $notification->academic_id = getAcademicId();
                        $notification->save();

                        try{
                            if($student_detail->user){
                                $title = app('translator')->get('student.attendance_notication');
                                Notification::send($student_detail->user, new FlutterAppNotification($notification,$title));
                            }

                        }
                        catch (\Exception $e) {
                            Log::info($e->getMessage());
                        }

                        // for parent user 
                        $parent = SmParent::find($student_detail->parent_id);
                        if($parent){
                            if($attendance->attendance_type == "P"){
                                $messege = app('translator')->get('student.Your_child_is_marked_present_in_the_attendance_on', ['date' => $date, 'student_name'=> $student_detail->full_name]);
                                
                            }
                            elseif($attendance->attendance_type == "L"){
                                $messege = app('translator')->get('student.Your_child_is_marked_late_in_the_attendance_on', ['date' => $date, 'student_name'=> $student_detail->full_name]);
                            }
                            elseif($attendance->attendance_type == "A"){
                                $messege = app('translator')->get('student.Your_child_is_marked_absent_in_the_attendance_on', ['date' => $date, 'student_name'=> $student_detail->full_name]);
                            }
                            elseif($attendance->attendance_type == "F"){
                                $messege = app('translator')->get('student.Your_child_is_marked_halfday_in_the_attendance_on', ['date' => $date, 'student_name'=> $student_detail->full_name]);
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
                                if($parent->parent_user){
                                    $title = app('translator')->get('student.attendance_notication');
                                    Notification::send($parent->parent_user, new FlutterAppNotification($notification,$title));
                                }

                            }
                            catch (\Exception $e) {
                                Log::info($e->getMessage());
                            }
                        }
                    }
                }
                // end
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('student-attendance');
        } catch (\Exception $e) {
            
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function studentAttendanceHoliday(Request $request)
    {
        if (moduleStatusCheck('University')) {
            $interface = App::make(UnCommonRepositoryInterface::class);
            $studentRecords  = $interface->searchStudentRecord($request)->get();
        } else {
            $studentRecords = StudentRecord::where('class_id', $request->class_id)
                            ->where('section_id', $request->section_id)
                            ->where('academic_id', getAcademicId())
                            ->where('school_id', Auth::user()->school_id)
                            ->get();
        }


        if ($studentRecords->isEmpty()) {
            Toastr::error('No Result Found', 'Failed');
            return redirect('student-attendance');
        }
        foreach ($studentRecords as $record) {

            $attendance = SmStudentAttendance::where('student_id', $record->student_id)
                        ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                        ->when(!moduleStatusCheck('University'), function ($query) use ($request) {
                            $query->where('class_id', $request->class_id);
                        })->when(!moduleStatusCheck('University'), function ($query) use ($request) {
                            $query->where('section_id', $request->section_id);
                        })
                        ->where('academic_id', getAcademicId())
                        ->where('student_record_id', $record->id)
                        ->where('school_id', Auth::user()->school_id)
                        ->first();
            if (!empty($attendance)) {
                $attendance->delete();
            }
            if ($request->purpose == "mark") {
                $attendance = new SmStudentAttendance();
                $attendance->attendance_type = "H";
                $attendance->notes = "Holiday";
                $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                $attendance->student_id = $record->student_id;
                $attendance->student_record_id = $record->id;
                $attendance->class_id = $record->class_id;
                $attendance->section_id = $record->section_id;
                $attendance->academic_id = getAcademicId();
                $attendance->school_id = Auth::user()->school_id;
                if (moduleStatusCheck('University')) {
                    $interface = App::make(UnCommonRepositoryInterface::class);
                    $interface->storeUniversityData($attendance, $request);
                }
                $attendance->save();

                $compact['holiday_date'] = date('Y-m-d', strtotime($request->attendance_date));
                @send_sms($record->student->mobile, 'holiday', $compact);
                @send_sms(@$record->student->parents->guardians_mobile, 'holiday', $compact);

                // futter notification
                $messege = "";
                $student = SmStudent::find($record->student_id);
                if($student){
                    $messege = app('translator')->get('student.Your_teacher_has_marked_holiday_in_the_attendance_on ', ['date' => dateconvert($attendance->attendance_date)]);
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


                    $parent = SmParent::find($student->parent_id);
                    if($parent){
                        $messege = app('translator')->get('student.Your_child_is_marked_holiday_in_the_attendance_on_date', ['date' => dateConvert($attendance->attendance_date), 'student_name' => $student->full_name. "'s"]);
                        $notification = new SmNotification();
                        $notification->user_id = $parent->user_id;
                        $notification->role_id = 3;
                        $notification->date = date('Y-m-d');
                        $notification->message = $messege;
                        $notification->school_id = Auth::user()->school_id;
                        $notification->academic_id = getAcademicId();
                        $notification->save();
    
                        try{
                            if($parent->parent_user){
                                $title = app('translator')->get('student.attendance_notication');
                                Notification::send($parent->parent_user, new FlutterAppNotification($notification,$title));
                            }
                        }
                        catch (\Exception $e) {
                            Log::info($e->getMessage());
                        }
                    }
    
    
                    $compact['holiday_date'] = date('Y-m-d', strtotime($request->attendance_date));
                    @send_sms($record->student->mobile, 'holiday', $compact);
                    

                }
                // end

            }
        }
        Toastr::success('Operation successful', 'Success');
        return redirect()->back();
    }

    public function studentAttendanceImport()
    {

        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.studentInformation.student_attendance_import', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function downloadStudentAtendanceFile()
    {

        try {
            $studentsArray = ['admission_no', 'class_id', 'section_id', 'attendance_date', 'in_time', 'out_time'];

            return Excel::create('student_attendance_sheet', function ($excel) use ($studentsArray) {
                $excel->sheet('student_attendance_sheet', function ($sheet) use ($studentsArray) {
                    $sheet->fromArray($studentsArray);
                });
            })->download('xlsx');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function studentAttendanceBulkStore(Request $request)
    {
        
        if (moduleStatusCheck('University')) {
            $request->validate([
                'un_session_id' =>'sometimes|nullable',
                'un_faculty_id' =>'required',
                'un_department_id' =>'required',
                'un_academic_id' =>'required',
                'un_semester_id' =>'required',
                'un_semester_label_id' =>'required',
                'un_section_id' =>'required',
                'file' =>'required',
                'attendance_date'=>'required',
            ]);
        }else{
            $request->validate([
                'attendance_date' => 'required',
                'file' => 'required',
                'class' => 'required',
                'section' => 'required',
            ]);
        }

        try {
            if (moduleStatusCheck('University')) {
                Excel::import(new UniversityStudentAttendanceImport($request->un_session_id, $request->un_faculty_id, $request->un_department_id, $request->un_academic_id, $request->un_semester_id, $request->un_semester_label_id, $request->un_section_id), $request->file('file'), 's3', \Maatwebsite\Excel\Excel::XLSX);
            } else {
            Excel::import(new StudentAttendanceImport($request->class, $request->section), $request->file('file'), 's3', \Maatwebsite\Excel\Excel::XLSX);
            }

            $data = StudentAttendanceBulk::get();
           

            if (!empty($data)) {
                $class_sections = [];
                $university_data = [];
                foreach ($data as $key => $value) {
                    if (date('d/m/Y', strtotime($request->attendance_date)) == date('d/m/Y', strtotime($value->attendance_date))) {
                        $class_sections[] = $value->class_id . '-' . $value->section_id;
                       
                    }
                    
                    if (moduleStatusCheck('University')) {
                        $university_data [] = $value->un_session_id . '-' .$value->un_faculty_id .'-'.
                        $value->un_department_id . '-' .$value->un_academic_id .'-'.
                        $value->un_semester_id . '-' .$value->un_semester_label_id .'-'. $value->un_section_id;
                    }
                }
              //  DB::beginTransaction();


                $all_student_ids = [];
                $present_students = [];
                $uniquesVales = moduleStatusCheck('University') ? $university_data : $class_sections;
                foreach (array_unique($uniquesVales) as $value) {
                    if (moduleStatusCheck('University')) {
                        $universityData = explode('-', $value);
                        $students = StudentRecord::where('un_session_id', $universityData[0])
                        ->where('un_faculty_id', $universityData[1])
                        ->where('un_department_id', $universityData[2])
                        ->where('un_academic_id', $universityData[3])
                        ->where('un_semester_id', $universityData[4])
                        ->where('un_semester_label_id', $universityData[5])
                        ->where('un_section_id', $universityData[6])
                        ->where('school_id', Auth::user()->school_id)
                        ->get();
                    } else {
                        $class_section = explode('-', $value);
                        $students = StudentRecord::where('class_id', $class_section[0])->where('section_id', $class_section[1])->where('school_id', Auth::user()->school_id)->get();
                        
                    }
                    foreach ($students as $student) {
                        StudentAttendanceBulk::where('student_record_id', $student->id)->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                            ->delete();
                        $all_student_ids[] = $student->id;
                    }

                }


                try {
                    
                    foreach ($data as $key => $value) {
                      
                        if ($value != "") {

                            if (date('d/m/Y', strtotime($request->attendance_date)) == date('d/m/Y', strtotime($value->attendance_date))) {
                                $student = StudentRecord::where('id', $value->student_record_id)->where('school_id', Auth::user()->school_id)->first();
                                if ($student != "") {
                                    // SmStudentAttendance
                                    $attendance_check = SmStudentAttendance::where('student_record_id', $student->id)
                                        ->where('attendance_date', date('Y-m-d', strtotime($value->attendance_date)))->first();
                                    if ($attendance_check) {
                                        $attendance_check->delete();
                                    }
                                    $present_students[] = $student->id;
                                    $import = new SmStudentAttendance();
                                    $import->student_id = $student->id;
                                    $import->student_record_id = $student->id;
                                    $import->attendance_date = date('Y-m-d', strtotime($value->attendance_date));
                                    $import->attendance_type = $value->attendance_type;
                                    $import->notes = $value->note;
                                    $import->school_id = Auth::user()->school_id;
                                    $import->academic_id = getAcademicId();
                                    if (moduleStatusCheck('University')) {
                                        $import->un_academic_id =$value->un_academic_id;
                                        $import->un_session_id =$value->un_session_id;
                                        $import->un_department_id =$value->un_department_id;
                                        $import->un_faculty_id =$value->un_faculty_id;
                                        $import->un_semester_id =$value->un_semester_id;
                                        $import->un_semester_label_id = $value->un_semester_label_id;
                                        $import->un_section_id = $value->un_section_id;
                                    }

            
                                  
                                    $import->save();
                                    $a[] = $import;
                                }
                               
                            } else {
                                // Toastr::error('Attendance Date not Matched', 'Failed');
                                StudentAttendanceBulk::where('student_id', $value->student_id)->delete();
                            }

                        }

                    }
                   
                } catch (\Exception $e) {
                   // DB::rollback();

                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
               // DB::commit();
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            }
        } catch (\Exception $e) {
             ;
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
        
    }

    public static function activeStudent()
    {
        $active_students = SmStudent::where('active_status', 1)
            ->where('school_id', Auth::user()->school_id)
            ->get();
        return $active_students;
    }
}