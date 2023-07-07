<?php

namespace App\Http\Controllers\Admin\Examination;

use App\User;
use App\SmExam;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmHoliday;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\YearCheck;
use App\SmExamType;
use App\SmClassRoom;
use App\SmClassTime;
use App\SmExamSetup;
use App\ApiBaseMethod;
use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\SmAcademicYear;
use App\SmExamSchedule;
use App\SmNotification;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\University\Entities\UnFaculty;
use Modules\University\Entities\UnSession;
use Modules\University\Entities\UnSemester;
use Illuminate\Support\Facades\Notification;
use Modules\University\Entities\UnDepartment;
use Modules\University\Entities\UnAcademicYear;
use Modules\University\Entities\UnSemesterLabel;
use App\Notifications\StudentExamCreateNotification;
use App\Http\Requests\Admin\Examination\SmExamScheduleSearchRequest;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;

class SmExamRoutineController extends Controller
{

    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function examSchedule()
    {
        try {
            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            if (teacherAccess()) {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
                    ->select('sm_classes.id','class_name')
                    ->distinct('sm_classes.id')
                    ->get();
            } else {
                $classes = SmClass::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id',Auth::user()->school_id)
                    ->get();
            }
            return view('backEnd.examination.exam_schedule', compact('classes', 'exam_types'));
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examScheduleCreate()
    {
        try {

            if (teacherAccess()) {
                $teacher_info=SmStaff::where('user_id', Auth::user()->id)->first();
                $classes= $teacher_info->classes;
            } else {
                $classes = SmClass::get();
            }
            $sections = SmSection::get();
            $subjects = SmSubject::get();
            $exams = SmExam::get();
            $exam_types = SmExamType::get();
            return view('backEnd.examination.exam_schedule_create', compact('classes', 'exams', 'exam_types'));
        } catch (\Exception $e) {
            
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function addExamRoutineModal($subject_id, $exam_period_id, $class_id, $section_id, $exam_term_id,$section_id_all)
    {
        try {
            $rooms = SmClassRoom::where('active_status', 1)
                ->where('school_id',Auth::user()->school_id)
                ->get();

            return view('backEnd.examination.add_exam_routine_modal', compact('subject_id', 'exam_period_id', 'class_id', 'section_id', 'exam_term_id', 'rooms','section_id_all'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function checkExamRoutinePeriod(Request $request)
    {

        try {
            $exam_period_check = SmExamSchedule::where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('exam_period_id', $request->exam_period_id)
                ->where('exam_term_id', $request->exam_term_id)
                ->where('date', date('Y-m-d', strtotime($request->date)))
                ->where('school_id',Auth::user()->school_id)
                ->first();

            return response()->json(['exam_period_check' => $exam_period_check]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function updateExamRoutinePeriod(Request $request)
    {

        try {
            $update_exam_period_check = SmExamSchedule::where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('exam_period_id', $request->exam_period_id)
                ->where('exam_term_id', $request->exam_term_id)
                ->where('date', date('Y-m-d', strtotime($request->date)))
                ->where('school_id',Auth::user()->school_id)
                ->first();

            return response()->json(['update_exam_period_check' => $update_exam_period_check]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function EditExamRoutineModal($subject_id, $exam_period_id, $class_id, $section_id, $exam_term_id, $assigned_id,$section_id_all)
    {

        try {
            $rooms = SmClassRoom::where('active_status', 1)
                ->where('school_id',Auth::user()->school_id)
                ->get();

            $assigned_exam = SmExamSchedule::find($assigned_id);

            return view('backEnd.examination.add_exam_routine_modal', compact('subject_id', 'exam_period_id', 'class_id', 'section_id', 'exam_term_id', 'rooms', 'assigned_exam','section_id_all'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteExamRoutineModal($assigned_id,$section_id_all)
    {

        try {
            return view('backEnd.examination.delete_exam_routine', compact('assigned_id','section_id_all'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function checkExamRoutineDate(Request $request)
    {

        try {
            if ($request->assigned_id == "") {
                $check_date = SmExamSchedule::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('exam_term_id', $request->exam_term_id)->where('date', date('Y-m-d', strtotime($request->date)))->where('exam_period_id', $request->exam_period_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $check_date = SmExamSchedule::where('id', '!=', $request->assigned_id)->where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('exam_term_id', $request->exam_term_id)->where('date', date('Y-m-d', strtotime($request->date)))->where('exam_period_id', $request->exam_period_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            }

            $holiday_check = SmHoliday::where('from_date', '<=', date('Y-m-d', strtotime($request->date)))->where('to_date', '>=', date('Y-m-d', strtotime($request->date)))->where('school_id',Auth::user()->school_id)->first();

            if ($holiday_check != "") {
                $from_date = date('jS M, Y', strtotime($holiday_check->from_date));
                $to_date = date('jS M, Y', strtotime($holiday_check->to_date));
            } else {
                $from_date = '';
                $to_date = '';
            }

            return response()->json([$check_date, $holiday_check, $from_date, $to_date]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examScheduleReportSearch(SmExamScheduleSearchRequest $request)
    {
        try {
            if(moduleStatusCheck('University')){

                $un_session = UnSession::find($request->un_session_id);
                $un_faculty = UnFaculty::find($request->un_faculty_id);
                $un_department= UnDepartment::find($request->un_department_id);
                $un_academic = UnAcademicYear::find($request->un_academic_id);
                $un_semester = UnSemester::find($request->un_semester_id);
                $un_semester_label = UnSemesterLabel::find($request->un_semester_label_id);
                $un_section = SmSection::find($request->un_section_id);

                $examName = SmExamType::where('id', $request->exam_type)
                            ->first()
                            ->title;

                $SmExamSchedule = SmExamSchedule::query();
                $exam_schedules = universityFilter($SmExamSchedule, $request)
                                ->where('exam_term_id', $request->exam_type)
                                ->with('subjectDetails')
                                ->get();

                $data['un_semester_label_id'] = $request->un_semester_label_id;
                $interface = App::make(UnCommonRepositoryInterface::class);
                $data = $interface->oldValueSelected($request);

                return view('backEnd.examination.exam_schedule',compact(
                    'exam_schedules',
                    'un_session',
                    'un_faculty',
                    'un_department',
                    'un_academic',
                    'un_semester',
                    'un_semester_label',
                    'un_section',
                    'examName',
                    ))->with($data);
            }else{
                $assign_subjects=SmAssignSubject::query();
                if(!empty($request->section)){
                    $assign_subjects ->where('section_id', $request->section);
                }
                $assign_subjects =  $assign_subjects ->where('class_id', $request->class)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                if ($assign_subjects->count() == 0) {
                    Toastr::error('No Subject Assigned. Please assign subjects in this class.', 'Failed');
                    return redirect()->back();
                    // return redirect('exam-schedule-create')->with('message-danger', 'No Subject Assigned. Please assign subjects in this class.');
                }

                $assign_subjects=SmAssignSubject::query();
                if(!empty($request->section)){
                    $assign_subjects ->where('section_id', $request->section);
                }
                $assign_subjects =  $assign_subjects ->where('class_id', $request->class)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id',Auth::user()->school_id)
                    ->get();
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
                $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
                $class_id = $request->class;
                if(empty($request->section)){
                    $section_id=0;
                }else{
                    $section_id = $request->section;
                }

                $exam_id = $request->exam_type;

                $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

                $exam_schedules = SmExamSchedule::query();
                if(!empty($request->section)){
                    $exam_schedules->where('section_id', $request->section);
                }
                $exam_schedules = $exam_schedules->where('exam_term_id', $exam_id)
                    ->where('class_id', $request->class)
                    ->where('school_id',Auth::user()->school_id)
                    ->get();

                //  return $exam_schedules;

                $exam_type_id=$request->exam_type;

                $examName     = SmExamType::where('id', $request->exam_type)->where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)->first()->title;
                $search_current_class   = SmClass::find($request->class);
                $search_current_section = SmSection::find($request->section);

                return view('backEnd.examination.exam_schedule_new', compact('classes', 'exams', 'exam_schedules', 'assign_subjects', 'class_id', 'section_id', 'exam_id', 'exam_types', 'exam_type_id', 'examName', 'search_current_class', 'search_current_section'));
            }
        } catch (\Exception $e) {
             ;
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function compareByTimeStamp($time1, $time2)
    {

        try {
            if (strtotime($time1) < strtotime($time2)) {
                return 1;
            } else if (strtotime($time1) > strtotime($time2)) {
                return -1;
            } else {
                return 0;
            }

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examScheduleReportSearchOld(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required',
        ]);

        try {
            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id',Auth::user()->school_id)->get();

            if ($assign_subjects->count() == 0) {
                Toastr::success('No Subject Assigned. Please assign subjects in this class.', 'Success');
                return redirect('exam-schedule-create');
            }

            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('school_id',Auth::user()->school_id)->get();

            $classes = SmClass::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $exams = SmExam::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();

            $class_id = $request->class;
            $section_id = $request->section;
            $exam_id = $request->exam;

            $exam_types = SmExamType::all();
            $exam_periods = SmClassTime::where('type', 'exam')->where('school_id',Auth::user()->school_id)->get();

            return view('backEnd.examination.exam_schedule', compact('classes', 'exams', 'assign_subjects', 'class_id', 'section_id', 'exam_id', 'exam_types', 'exam_periods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function examSchedulePrint(Request $request)
    {

        try {
            $assign_subjects = SmAssignSubject::query();

            if($request->section_id !=0){
                $assign_subjects->where('section_id', $request->section_id);
            }
            $assign_subjects =$assign_subjects->where('class_id', $request->class_id)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->distinct(['section_id','subject_id'])
                ->get();

            $exam_periods = SmClassTime::where('type', 'exam')
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();

            $academic_year=SmAcademicYear::find(getAcademicId());

            $class_id = $request->class_id;

            // if($request->section_id==0){
            //     $section_id='All Sections';
            // }else{
            //     $section_id = $request->section_id;
            // }
            $exam_id = $request->exam_id;
            $print = request()->print;
            return view(
                'backEnd.examination.exam_schedult_print',
                [
                    'assign_subjects' => $assign_subjects,
                    'exam_periods' => $exam_periods,
                    'class_id' => $request->class_id,
                    'academic_year' => $academic_year,

                    'section_id' => $request->section_id,
                    'exam_id' => $request->exam_id,
                    'print' => $print,
                ]
            );

//            $pdf = PDF::loadView(
//                'backEnd.examination.exam_schedult_print',
//                [
//                    'assign_subjects' => $assign_subjects,
//                    'exam_periods' => $exam_periods,
//                    'class_id' => $request->class_id,
//                    'academic_year' => $academic_year,
//
//                    'section_id' => $request->section_id,
//                    'exam_id' => $request->exam_id,
//                ]
//            )->setPaper('A4', 'landscape');
//            return $pdf->stream('EXAM_SCHEDULE.pdf');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examRoutineReport(Request $request)
    {

        try {
            $exam_types = SmExamType::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            return view('backEnd.reports.exam_routine_report', compact('classes', 'exam_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examRoutineReportSearch(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'sometimes|nullable',
        ]);

        try {


            $classes = SmClass::get();
            $exams = SmExam::get();
            $class_id = $request->class ? $request->class : 0;
            $section_id = $request->section ? $request->section : 0;
            $exam_id = $request->exam;

            $exam_types = SmExamType::get();

            $exam_schedules = SmExamSchedule::where('school_id', Auth::user()->school_id)

                ->when($request->exam, function ($query) use ($request) {
                    $query->where('exam_term_id', $request->exam);
                })
                ->when($request->class, function ($query) use ($request) {
                    $query->where('class_id', $request->class);
                })
                ->when($request->section, function ($query) use ($request) {
                    $query->where('section_id', $request->section);
                })
                ->get();

            $exam_type_id=$request->exam;

            $examName     = SmExamType::where('id', $request->exam)->first()->title;
            $search_current_class   = SmClass::find($request->class);
            $search_current_section = SmSection::find($request->section);

            return view('backEnd.reports.exam_routine_report', compact('classes', 'exams', 'exam_schedules','class_id', 'section_id', 'exam_id', 'exam_types', 'exam_type_id', 'examName', 'search_current_class', 'search_current_section'));
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function examRoutineReportSearchPrint($exam_id)
    {

        try {
            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_routines = SmExamSchedule::where('exam_term_id', $exam_id)->orderBy('date', 'ASC')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_routines = $exam_routines->groupBy('date');
            $academic_year = SmAcademicYear::find(getAcademicId());


        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examScheduleSearch(SmExamScheduleSearchRequest $request)
    {
        try {
            if (moduleStatusCheck('University')) {
                $data= [];
                $exam_type_id = $request->exam_type;

                $un_session = UnSession::find($request->un_session_id);
                $un_faculty = UnFaculty::find($request->un_faculty_id);
                $un_department= UnDepartment::find($request->un_department_id);
                $un_academic = UnAcademicYear::find($request->un_academic_id);
                $un_semester = UnSemester::find($request->un_semester_id);
                $un_semester_label = UnSemesterLabel::find($request->un_semester_label_id);
                $un_section = SmSection::find($request->un_section_id);


                $SmExam = SmExam::query();
                $subjects = universityFilter($SmExam, $request)
                            ->where('exam_type_id', $request->exam_type)
                            ->with('subjectDetails')
                            ->orWhereNull('un_section_id')
                            ->get();

                $teachers = SmStaff::where('role_id', 4)->where('active_status', 1)
                            ->where('school_id', auth()->user()->school_id)
                            ->get(['id','user_id','full_name']);

                $rooms = SmClassRoom::where('active_status', 1)->where('school_id', auth()->user()->school_id)
                        ->get(['id','room_no']);

                $examName = SmExamType::where('id', $request->exam_type)
                        ->where('active_status', 1)
                        ->first()
                        ->title;

                $SmExamSchedule = SmExamSchedule::query();
                $exam_schedule = universityFilter($SmExamSchedule, $request)
                                ->where('exam_term_id', $request->exam_type)
                                ->get();

                $rooms = SmClassRoom::where('active_status', 1)
                        ->where('school_id', auth()->user()->school_id)
                        ->get(['id','room_no']);

                $data['un_semester_label_id'] = $request->un_semester_label_id;
                $interface = App::make(UnCommonRepositoryInterface::class);
                $data = $interface->oldValueSelected($request);

                return view('backEnd.examination.exam_schedule_create',
                compact('examName',
                        'un_session',
                        'un_faculty',
                        'un_department',
                        'un_academic',
                        'un_semester',
                        'un_semester_label',
                        'un_section',
                        'exam_type_id',
                        'exam_schedule',
                        'teachers',
                        'rooms',
                        'subjects',
                    ))->with($data);
            }else{
                $subject_ids     = SmExamSetup::query();
                $assign_subjects = SmAssignSubject::query();

                if ($request->class !=null) {
                    $assign_subjects->where('class_id', $request->class);
                    $subject_ids->where('class_id', $request->class);
                }

                if ($request->section !=null) {
                    $assign_subjects->where('section_id', $request->section);
                    $subject_ids->where('section_id', $request->section);
                }


                $assign_subjects = $assign_subjects->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
                $subject_ids    = $subject_ids->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->where('exam_term_id', $request->exam_type)
                    ->pluck('subject_id')->toArray();


                if ($assign_subjects->count() == 0) {
                    Toastr::success('No Subject Assigned. Please assign subjects in this class.', 'Success');
                    return redirect('exam-schedule-create');
                }



                if (teacherAccess()) {
                    $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                    $classes      = $teacher_info->classes;
                } else {
                    $classes      = SmClass::get();
                }


                $class_id     = $request->class;
                $section_id   = $request->section !=null ? $request->section : 0;
                $exam_type_id = $request->exam_type;
                $exam_types   = SmExamType::where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                $exam_schedule = SmExamSchedule::query();
                if ($request->class) {
                    $exam_schedule->where('class_id', $request->class);
                }
                if ($request->section) {
                    $exam_schedule->where('section_id', $request->section);
                }
                $exam_schedule = $exam_schedule->where('exam_term_id', $request->exam_type)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                $subjects     = SmSubject::whereIn('id', $subject_ids)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get(['id','subject_name']);

                $teachers     = SmStaff::where('role_id', 4)->where('active_status', 1)
                    ->where('school_id', Auth::user()->school_id)
                    ->get(['id','user_id','full_name']);

                $rooms        = SmClassRoom::where('active_status', 1)->where('school_id', Auth::user()->school_id)
                    ->get(['id','room_no']);

                $examName     = SmExamType::where('id', $request->exam_type)->where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)->first()->title;

                $search_current_class   = SmClass::find($request->class);
                $search_current_section = SmSection::find($request->section);

                return view('backEnd.examination.exam_schedule_new_update', compact('classes', 'subjects', 'exam_schedule', 'class_id', 'section_id', 'exam_type_id', 'exam_types', 'teachers', 'rooms', 'examName', 'search_current_class', 'search_current_section'));
            }

        } catch (\Exception $e) {
            Toastr::error('No Result Found!', 'Failed');
            return redirect()->back();
        }
    }

    //end




    public function addExamRoutineStore(Request $request)
    {

        if(db_engine()=="mysql"){
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
        try {
            if(moduleStatusCheck('University')){
                $exam_type_id = $request->exam_type_id;

                $SmExamSchedule = SmExamSchedule::query();
                $exam_schedule = universityFilter($SmExamSchedule, $request)
                                ->where('exam_term_id', $exam_type_id)
                                ->delete();

                foreach ($request->routine as $routine_data) {

                    $sm_exam = SmExam::where('exam_type_id', $exam_type_id)
                                    ->where('un_semester_label_id', $request->un_semester_label_id)
                                    ->where('un_subject_id', gv($routine_data, 'subject'))
                                    ->first();  

                    if (gv($routine_data, 'subject')=="Select Subject *") {
                        Toastr::error('Subject Can not Be Empty', 'Failed');
                    }
                    if (!gv($routine_data, 'subject') || gv($routine_data, 'subject')=="Select Subject *" || !gv($routine_data, 'start_time') || !gv($routine_data, 'end_time')) {
                        continue;
                    }
                    $is_exist= SmExamSchedule::where(
                        [
                            'exam_term_id'=>$exam_type_id,
                            'exam_id'=> $sm_exam->id,
                            'subject_id'=>gv($routine_data, 'subject'),
                            'date'=> date('Y-m-d', strtotime(gv($routine_data, 'date'))),
                            'start_time' =>  date('H:i:s', strtotime(gv($routine_data, 'start_time'))),
                            'end_time' => date('H:i:s', strtotime(gv($routine_data, 'end_time'))),
                            'room_id' => gv($routine_data, 'room'),
                            'un_session_id' => $request->un_session_id,
                            'un_faculty_id' => $request->un_faculty_id,
                            'un_department_id' => $request->un_department_id,
                            'un_academic_id' => $request->un_academic_id,
                            'un_semester_id' => $request->un_semester_id,
                            'un_semester_label_id' => $request->un_semester_label_id,
                            'un_section_id' => $request->un_section_id,
                        ]
                    )->where('school_id', Auth::user()->school_id)->first();

                    if ($is_exist) {
                        continue;
                    }

                    $exam_routine = new SmExamSchedule();
                    $exam_routine->exam_term_id = $exam_type_id;
                    $exam_routine->exam_id = $sm_exam->id;

                    $common = App::make(UnCommonRepositoryInterface::class);
                    $common->storeUniversityData($exam_routine, $request);

                    $exam_routine->un_subject_id = gv($routine_data, 'subject');
                    $exam_routine->teacher_id = gv($routine_data, 'teacher_id');
                    $exam_routine->date = date('Y-m-d', strtotime(gv($routine_data, 'date')));
                    $exam_routine->start_time = date('H:i:s', strtotime(gv($routine_data, 'start_time')));
                    $exam_routine->end_time = date('H:i:s', strtotime(gv($routine_data, 'end_time')));
                    $exam_routine->room_id = gv($routine_data, 'room');
                    $exam_routine->school_id = Auth::user()->school_id;
                    $exam_routine->academic_id = getAcademicId();
                    $exam_routine->save();
                }

                Toastr::success('Exam routine has been assigned successfully', 'Success');
                return redirect('exam-schedule');
            }else{
                $class_id   = $request->class_id;
                $section_id = $request->section_id == 0 ? 0 : $request->section_id;
                $exam_term_id = $request->exam_type_id;

                $exam_schedule  = SmExamSchedule::query();
                if ($request->class_id) {
                    $exam_schedule->where('class_id', $request->class_id);
                }
                if ($request->section_id !=0) {
                    $exam_schedule->where('section_id', $request->section);
                }
                $exam_schedule = $exam_schedule->where('exam_term_id', $request->exam_type_id)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->delete();

                    foreach ($request->routine as $routine_data) {
                        if (gv($routine_data, 'subject')=="Select Subject *") {
                            Toastr::error('Subject Can not Be Empty', 'Failed');
                            return redirect('exam-routine-view/' . $class_id . '/' . $section_id . '/' . $exam_term_id);
                        }
                        if (!gv($routine_data, 'subject') || gv($routine_data, 'subject')=="Select Subject *" || !gv($routine_data, 'start_time') || !gv($routine_data, 'end_time')) {
                            continue;
                        }

                        $is_exist= SmExamSchedule::where(
                            [
                                'exam_term_id'=>$request->exam_type_id,
                                'subject_id'=>gv($routine_data, 'subject'),
                                'date'=> date('Y-m-d', strtotime(gv($routine_data, 'date'))),
                                'start_time' =>  date('H:i:s', strtotime(gv($routine_data, 'start_time'))),
                                'end_time' => date('H:i:s', strtotime(gv($routine_data, 'end_time'))),
                                'room_id' => gv($routine_data, 'room'),
                                'class_id' => $request->class_id,
                                'section_id' => gv($routine_data, 'section')
                            ]
                        )->where('school_id', Auth::user()->school_id)->first();


                        if ($is_exist) {
                            continue;
                        }

                        $exam_routine = new SmExamSchedule();
                        $exam_routine->exam_term_id = $request->exam_type_id;
                        $exam_routine->class_id = $request->class_id;
                        $exam_routine->section_id = gv($routine_data, 'section');
                        $exam_routine->subject_id = gv($routine_data, 'subject');
                        $exam_routine->teacher_id = gv($routine_data, 'teacher_id');
                        $exam_routine->date = date('Y-m-d', strtotime(gv($routine_data, 'date')));
                        $exam_routine->start_time = date('H:i:s', strtotime(gv($routine_data, 'start_time')));
                        $exam_routine->end_time = date('H:i:s', strtotime(gv($routine_data, 'end_time')));
                        $exam_routine->room_id = gv($routine_data, 'room');
                        $exam_routine->school_id = Auth::user()->school_id;
                        $exam_routine->academic_id = getAcademicId();
                        $exam_routine->save();
                    }

                Toastr::success('Exam routine has been assigned successfully', 'Success');
                return redirect('exam-routine-view/' . $class_id . '/' . $section_id . '/' . $exam_term_id);
            }
        } catch (\Exception $e) {
       
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examRoutineView($class_id, $section_id, $exam_term_id)
    {

        try {

            $subject_ids = SmExamSetup::query();

            if($class_id !=null){
                $subject_ids->where('class_id', $class_id);
            }

            if($section_id !=0){
                $subject_ids->where('section_id', $section_id);
            }

            $subject_ids  = $subject_ids->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->where('exam_term_id', $exam_term_id)
                ->pluck('subject_id')->toArray();

            $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();
            $exams        = SmExam::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();

            $exam_type_id = $exam_term_id;

            $exam_types   = SmExamType::where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();
            $exam_periods = SmClassTime::where('type', 'exam')
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();
            $rooms        = SmClassRoom::where('active_status', 1)
                ->where('school_id',Auth::user()->school_id)
                ->get();

            $subjects     = SmSubject::whereIn('id',$subject_ids)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get(['id','subject_name']);

            $teachers     = SmStaff::where('role_id',4)->where('active_status',1)
                ->where('school_id',Auth::user()->school_id)
                ->get(['id','user_id','full_name']);


            $search_current_class   = SmClass::find($class_id);
            $search_current_section = SmSection::find($section_id);

            if($section_id ==0){
                $exam_schedule          = SmExamSchedule::where('class_id',$class_id)->where('exam_term_id',$exam_type_id)->get();
            }else{
                $exam_schedule          = SmExamSchedule::where('class_id',$class_id)->where('section_id',$section_id)
                    ->where('exam_term_id',$exam_type_id)->get();
            }

            $examName               = SmExamType::where('id',$exam_type_id)->where('active_status',1)
                ->where('school_id',Auth::user()->school_id)
                ->first()->title;

            return view('backEnd.examination.exam_schedule_new_update', compact('classes','subjects','exam_schedule','exams', 'class_id', 'section_id', 'exam_type_id', 'exam_types','teachers','rooms','examName','search_current_class','search_current_section'));
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function examRoutinePrint($class_id, $section_id, $exam_term_id)
    {
        try {
            $exam_type_id   = $exam_term_id;
            $exam_type      = SmExamType::find($exam_type_id)->title;
            $academic_id    = SmExamType::find($exam_type_id)->academic_id;
            $academic_year  = SmAcademicYear::find($academic_id);
            $class_name     = $class_id !=0 ? SmClass::find($class_id)->class_name : 'All Classes';
            $section_name   = $section_id !=0 ? SmSection::find($section_id)->section_name :'All Sections';
            $exam_schedules = SmExamSchedule::where('school_id', Auth::user()->school_id)
                ->when($exam_term_id, function ($query) use ($exam_term_id) {
                    $query->where('exam_term_id', $exam_term_id);
                })
                ->when($class_id !=0, function ($query) use ($class_id) {
                    $query->where('class_id', $class_id);
                })
                ->when($section_id !=0, function ($query) use ($section_id) {
                    $query->where('section_id', $section_id);
                })
                ->get();

            // return view('backEnd.examination.exam_schedule_print', [
            //     'exam_schedules' => $exam_schedules,
            //     'exam_type' => $exam_type,
            //     'class_name' => $class_name,
            //     'academic_year' => $academic_year,
            //     'section_name' => $section_name,
            // ]);
            $print = request()->print;
            return view('backEnd.examination.exam_schedule_print',
                [
                    'exam_schedules' => $exam_schedules,
                    'exam_type' => $exam_type,
                    'class_name' => $class_name,
                    'academic_year' => $academic_year,
                    'section_name' => $section_name,
                    'print' => $print,
                ]);

            $pdf = PDF::loadView(
                'backEnd.examination.exam_schedule_print',
                [
                    'exam_schedules' => $exam_schedules,
                    'exam_type' => $exam_type,
                    'class_name' => $class_name,
                    'academic_year' => $academic_year,
                    'section_name' => $section_name,


                ]
            )->setPaper('A4', 'landscape');
            return $pdf->stream('EXAM_SCHEDULE.pdf');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function deleteExamRoutine(Request $request)
    {

        try {

            $exam_routine = SmExamSchedule::find($request->id);
            $result = $exam_routine->delete();

            return response(["done"]);

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}