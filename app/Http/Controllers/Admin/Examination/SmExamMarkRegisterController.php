<?php

namespace App\Http\Controllers\Admin\Examination;

use App\SmExam;
use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\YearCheck;
use App\SmExamType;
use App\SmExamSetup;
use App\SmMarkStore;
use App\SmMarksGrade;
use App\SmResultStore;
use App\SmExamSchedule;
use App\SmAssignSubject;
use App\SmExamAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmExamAttendanceChild;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\University\Entities\UnFaculty;
use Modules\University\Entities\UnSession;
use Modules\University\Entities\UnSubject;
use Modules\University\Entities\UnSemester;
use Modules\University\Entities\UnDepartment;
use Modules\University\Entities\UnAcademicYear;
use Modules\University\Entities\UnSemesterLabel;
use App\Http\Requests\Admin\Examination\AddMarkRequest;
use Modules\University\Entities\UnSubjectAssignStudent;
use App\Http\Controllers\Admin\StudentInfo\SmStudentReportController;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;

class SmExamMarkRegisterController extends Controller
{
        // Mark Register View Page
        public function index()
        {
            try {
                $exams = SmExam::get();
                if (teacherAccess()) {
                    $teacher_info = SmStaff::where('user_id',Auth::user()->id)->first();
                   $classes= $teacher_info->classes;
                } else {
                   $classes = SmClass::get();
                }
                
                $exam_types = SmExamType::get();
                return view('backEnd.examination.masks_register', compact('exams', 'classes', 'exam_types'));
            }catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
    
        public function create()
        {
            try{
                $exams = SmExam::get();
    
                $exam_types = SmExamType::get();
    
                 if (teacherAccess()) {
                    $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                    $classes= $teacher_info->classes;
                } else {
                   $classes = SmClass::get();
                }
                $subjects = SmSubject::get();
                return view('backEnd.examination.masks_register_create', compact('exams', 'classes', 'subjects', 'exam_types'));
            }catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
    public function search(AddMarkRequest $request)
    {
        
        try {
            if (moduleStatusCheck('University')) {
                $data = [];
                $un_section = null;
                if ($request->un_section_id) {
                    $un_section = SmSection::find($request->un_section_id);
                }
                $un_session = UnSession::find($request->un_session_id);
                $un_faculty = UnFaculty::find($request->un_faculty_id);
                $un_department= UnDepartment::find($request->un_department_id);
                $un_academic = UnAcademicYear::find($request->un_academic_id);
                $un_semester = UnSemester::find($request->un_semester_id);
                $un_semester_label = UnSemesterLabel::find($request->un_semester_label_id);
                $un_section = SmSection::find($request->un_section_id);

                $SmExam = SmExam::query();
                $sm_exam = universityFilter($SmExam, $request)
                            ->where('exam_type_id', $request->exam_type)
                            ->where('un_subject_id', $request->subject_id)
                            ->orWhereNull('un_section_id')
                            ->first();


                $exam_id = $sm_exam->id;
                $exam_type_id = $request->exam_type;
                $subject_id = $request->subject_id;
                $exam_type = SmExamType::find($request->exam_type);
                $subjectName = UnSubject::find($request->subject_id);

                $assignSubjects = UnSubjectAssignStudent::query();
                $students = unFilterBySub($assignSubjects, $request)
                           ->whereHas('studentDetail', function ($q)  {
                               $q->where('active_status', 1);
                           })->get();
                                  
                $exam_schedule = SmExamSchedule::where('exam_id', $sm_exam->id)
                                ->where('academic_id', getAcademicId())
                                ->first();

                $data['un_semester_label_id'] = $request->un_semester_label_id;
                $interface = App::make(UnCommonRepositoryInterface::class);
                $data = $interface->oldValueSelected($request);
                               
                if ($students->count() < 1 ) {
                    Toastr::error('Student is not found in according this class and section!', 'Failed');
                    return redirect()->back();
                } else {
                    $SmExamSetu = SmExamSetup::query();
            
                    $marks_entry_form = $SmExamSetu->where('un_semester_label_id',$request->un_semester_label_id);
                   
                    $marks_entry_form->where(
                            [
                                ['exam_term_id', $request->exam_type],
                                ['un_subject_id', $request->subject_id],
                                ['un_section_id', $request->un_section_id]
                            ]
                        );
                        $marks_entry_form = $SmExamSetu->get();
                       
                    if ($marks_entry_form->count() > 0) {
                        $number_of_exam_parts = count($marks_entry_form); 
                        return view('backEnd.examination.masks_register_create', compact(
                            'students',
                            'number_of_exam_parts',
                            'marks_entry_form',
                            'exam_id',
                            'subject_id',
                            'un_session',
                            'un_faculty',
                            'un_department',
                            'un_academic',
                            'un_semester',
                            'un_semester_label',
                            'un_section',
                            'subjectName',
                            'exam_type',
                            'exam_type_id',
                            'un_section',
                        ))->with($data);
                    } else {
                       
                        Toastr::error('No result found or exam setup is not done!', 'Failed');
                        return redirect()->back();
                    }
                }
                return view('backEnd.examination.masks_register_create', compact(
                    'students',
                    'exam_id',
                    'subject_id',
                    'marks_register_subjects',
                    'assign_subject_ids',
                    'un_session',
                    'un_faculty',
                    'un_department',
                    'un_academic',
                    'un_semester',
                    'un_semester_label',
                    'subjectName',
                    'exam_type',
                    'exam_type_id',
                ))->with($data);
            } else {
                if ($request->section=='') {

                    $classSections=SmAssignSubject::where('class_id', $request->class)
                                                ->where('subject_id', $request->subject)
                                                ->where('school_id', auth()->user()->school_id)
                                                ->where('academic_id', getAcademicId())
                                                ->get(['section_id']);
    
                    $exam_attendance = SmExamAttendance::where('class_id', $request->class)
                                                        ->where('exam_id', $request->exam)
                                                        ->where('subject_id', $request->subject)
                                                        ->first();
                } else {
                    $exam_attendance = SmExamAttendance::where('class_id', $request->class)->where('section_id', $request->section)->where('exam_id', $request->exam)->where('subject_id', $request->subject)->first();
                }

                if ($exam_attendance == "" && !isSkip('exam_attendance')) {
                    Toastr::error('Exam Attendance not taken yet, please check exam attendance', 'Failed');
                    return redirect()->back();
                }

                $exams = SmExam::get();
                $classes = SmClass::get();
                $exam_types = SmExamType::get();
                $exam_id = $request->exam;
                $class_id = $request->class;
                $section_id = $request->section;
                $subject_id = $request->subject;
                $subjectNames = SmSubject::where('id', $subject_id)->first();
    
                $exam_type = SmExamType::find($request->exam);
                $class = SmClass::find($request->class);
                $section = SmSection::find($request->section);
    
                $search_info['exam_name'] = $exam_type->title;
                $search_info['class_name'] = $class->class_name;
                if ($request->section !='') {
                    $search_info['section_name'] = $section->section_name;
                } else {
                    $search_info['section_name'] = 'All Sections';
                }

                $students = StudentRecord::with('class', 'section')
                ->when($request->academic_year, function ($query) use ($request) {
                    $query->where('academic_id', $request->academic_year);
                })
                ->whereHas('studentDetail', function ($q)  {
                    $q->where('active_status', 1);
                })
                ->when($request->class, function ($query) use ($request) {
                    $query->where('class_id', $request->class);
                })
                ->when($request->section, function ($query) use ($request) {
                    $query->where('section_id', $request->section);
                })
                ->when(!$request->academic_year, function ($query) use ($request) {
                    $query->where('academic_id', getAcademicId());
                })->where('school_id', auth()->user()->school_id)->where('is_promote', 0)->whereHas('studentDetail', function($q){
                        $q->where('active_status', 1);
                    })->where('is_promote', 0)->get()->sortBy('roll_no');

                $exam_schedule = SmExamSchedule::where('exam_id', $request->exam)->where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->first();

                if ($students->count() < 1) {
                    Toastr::error('Student is not found in according this class and section!', 'Failed');
                    return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Student is not found in according this class and section! Please add student in this section of that class.');
                } else {
                    if($request->section !=''){
                    $marks_entry_form = SmExamSetup::with('class','section')->where(
                        [
                            ['exam_term_id', $exam_id],
                            ['class_id', $class_id],
                            ['section_id', $section_id],
                            ['subject_id', $subject_id]
                        ]
                        )->where('academic_id', getAcademicId())->get();
                    }else {
                        $marks_entry_form = SmExamSetup::with('class','section')->where(
                        [
                            ['exam_term_id', $exam_id],
                            ['class_id', $class_id],                    
                            ['subject_id', $subject_id]
                        ]
                        )->whereIn('section_id',$classSections)->where('academic_id', getAcademicId())->orderby('id','ASC')->get();
                    }


                    if ($marks_entry_form->count() > 0) {
                        $number_of_exam_parts = count($marks_entry_form);
                        return view('backEnd.examination.masks_register_create', compact('exams', 'classes', 'students', 'exam_id', 'class_id', 'section_id', 'subject_id', 'subjectNames', 'number_of_exam_parts', 'marks_entry_form', 'exam_types','search_info'));
                    } else {
                        Toastr::error('No result found or exam setup is not done!', 'Failed');
                        return redirect()->back();
                        // return redirect()->back()->with('message-danger', 'No result found or exam setup is not done!');
                    }
                
                    return view('backEnd.examination.masks_register_create', compact('exams', 'classes', 'students',   'exam_id', 'class_id', 'section_id', 'marks_register_subjects', 'assign_subject_ids','search_info'));
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if(moduleStatusCheck('University')){
                $abc = [];
                $exam_id = $request->exam_id;
                $exam_type_id = $request->exam_type_id;
                $subject_id = $request->subject_id;
                $counter = 0;
             
                foreach ($request->markStore as $record_id => $record) {
                    $sid = gv($record, 'student');
                    $marks = gv($record, 'marks', []);
                    $absent_students= array(gv($record, 'absent_students'));
                    $admission_no   = gv($record, 'admission_no');
                    $roll_no        = gv($record, 'roll_no');
                   
                    if (!empty($marks)) {
                        $exam_setup_count = 0;
                        $total_marks_persubject = 0;
                        foreach ($marks as $part_mark) {
                            $mark_by_exam_part = ($part_mark == null) ? 0 : $part_mark;
                            $total_marks_persubject = $total_marks_persubject + $mark_by_exam_part;
                            $exam_setup_id = gv($record, 'exam_Sids', [])[$exam_setup_count];

                            $SmMarkStore = SmMarkStore::query();
                            $previous_record = universityFilter($SmMarkStore, $request)
                                ->where([
                                    ['un_subject_id', $subject_id],
                                    ['exam_term_id', $exam_type_id],
                                    ['student_record_id', $record_id],
                                    ['exam_setup_id', $exam_setup_id],
                                    ['student_id', $sid]
                                ])
                                ->where('academic_id', getAcademicId())
                                ->first();
                               
        
                            if ($previous_record == "" || $previous_record == null) {
                                $marks_register = new SmMarkStore();
                                $marks_register->exam_term_id = $exam_type_id;
                                $marks_register->un_subject_id = $subject_id;
                                $marks_register->student_id = $sid;
                                $marks_register->student_record_id = $record_id;
                                $marks_register->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                $marks_register->total_marks = $mark_by_exam_part;
                                $marks_register->exam_setup_id = $exam_setup_id;
                                $common = App::make(UnCommonRepositoryInterface::class);
                                $common->storeUniversityData($marks_register, $request);

                                if (isset($absent_students)) {
                                    if (in_array($record_id, $absent_students)) {
                                        $marks_register->is_absent = 1;
                                    } else {
                                        $marks_register->is_absent = 0;
                                    }
                                }
                                $marks_register->teacher_remarks = gv($record, 'teacher_remarks');
                                $marks_register->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                $marks_register->school_id = Auth::user()->school_id;
                                $marks_register->un_academic_id = getAcademicId();
                              
                                $marks_register->save();
                                $marks_register->toArray();
                               
                            } else {
                                    $pid = $previous_record->id;
                                    $marks_register = SmMarkStore::find($pid);
                                    $marks_register->total_marks = $mark_by_exam_part;
        
                                if (isset($absent_students)) {
                                    if (in_array($record_id, $absent_students)) {
                                            $marks_register->is_absent = 1;
                                    } else {
                                            $marks_register->is_absent = 0;
                                    }
                                }
                                $marks_register->teacher_remarks = gv($record, 'teacher_remarks');
                                    $marks_register->save();
                            }
                            $exam_setup_count++;
                        }
                        
        
                        $subject_full_mark = un_subjectFullMark($request->exam_type_id, $subject_id, $request);
                        $student_obtained_mark = $total_marks_persubject;
                        $mark_by_persentage = subjectPercentageMark($student_obtained_mark, $subject_full_mark);
                            
                        $mark_grade = SmMarksGrade::where([
                                            ['percent_from', '<=', $mark_by_persentage], 
                                            ['percent_upto', '>=', $mark_by_persentage]])
                                            ->where('academic_id', getAcademicId())
                                            ->where('school_id', Auth::user()->school_id)
                                            ->first();
                        $abc[] = $total_marks_persubject;
        
                            $SmResultStore = SmResultStore::query();
                            $previous_result_record = universityFilter($SmResultStore, $request)
                                                        ->where([
                                                            ['un_subject_id', $subject_id],
                                                            ['exam_type_id', $exam_type_id],
                                                            ['student_record_id', $record_id],
                                                            ['student_id', $sid]
                                                        ])->first();
        
                       
                            if ($previous_result_record == "" || $previous_result_record == null) {
                                $result_record = new SmResultStore();
                                $result_record->un_subject_id = $subject_id;
                                $result_record->exam_type_id = $exam_type_id;
                                $result_record->student_id = $sid;
                                $result_record->student_record_id = $record_id;

                                $common = App::make(UnCommonRepositoryInterface::class);
                                $common->storeUniversityData($result_record, $request);
        
                            if (isset($absent_students)) {
                                if (in_array($record_id, $absent_students)) {
                                    $result_record->is_absent = 1;
                                } else {
                                    $result_record->is_absent = 0;
                                }
                            }
                            $result_record->total_marks            =   $total_marks_persubject;
                            $result_record->total_gpa_point        =   @$mark_grade->gpa;
                            $result_record->total_gpa_grade        =   @$mark_grade->grade_name;
                            $result_record->teacher_remarks        =   gv($record, 'teacher_remarks');
                            $result_record->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            $result_record->school_id = Auth::user()->school_id;
                            $result_record->un_academic_id = getAcademicId();
                            $result_record->save();
                            $result_record->toArray();
                            
                        } else {
                                $id = $previous_result_record->id;
                                $result_record = SmResultStore::find($id);
                                $result_record->total_marks            =   $total_marks_persubject;
                                $result_record->total_gpa_point        =   @$mark_grade->gpa;
                                $result_record->total_gpa_grade        =   @$mark_grade->grade_name;
                                $result_record->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            if (isset($absent_students)) {
                                if (in_array($record_id, $absent_students)) {
                                    $result_record->is_absent = 1;
                                } else {
                                    $result_record->is_absent = 0;
                                }
                            }
                            $result_record->teacher_remarks = gv($record, 'teacher_remarks');
                            $result_record->save();
                            $result_record->toArray();
                        }
                    }
                } //end student loop
            }else{
                $abc = [];
                $class_id = $request->class_id;
                if ($request->section_id !='') {
                    $section_id = $request->section_id;
                }
                $subject_id = $request->subject_id;
                $exam_id = $request->exam_id;
                $counter = 0;           // Initilize by 0
        
                foreach ($request->markStore as $record_id => $record) {
                    $sid            =   gv($record, 'student');
                    $marks          =   gv($record, 'marks', []);
                    $absent_students= array(gv($record, 'absent_students'));
                
                    if ($request->section_id=='') {
                        $section_id = gv($record, 'section');
                    }
                    $admission_no   = gv($record, 'admission_no');
                    $roll_no        = gv($record, 'roll_no');
                    if (!empty($marks)) {
                        $exam_setup_count = 0;
                        $total_marks_persubject = 0;
                        foreach ($marks as $part_mark) {
                            $mark_by_exam_part = ($part_mark == null) ? 0 : $part_mark;
                                // 0=If exam part is empty
                            $total_marks_persubject = $total_marks_persubject + $mark_by_exam_part;
                                // $is_absent = ($request->abs[$sid]==null) ? 0 : 1;
                            $exam_setup_id = gv($record, 'exam_Sids', [])[$exam_setup_count];
                                
                            $previous_record = SmMarkStore::where([
                                    ['class_id', $class_id],
                                    ['section_id', $section_id],
                                    ['subject_id', $subject_id],
                                    ['exam_term_id', $exam_id],
                                    ['student_record_id', $record_id],
                                    ['exam_setup_id', $exam_setup_id],
                                    ['student_id', $sid]
                                ])->where('academic_id', getAcademicId())->first();
                                // Is previous record exist ?
        
                            if ($previous_record == "" || $previous_record == null) {
        
                                $marks_register = new SmMarkStore();
                                $marks_register->exam_term_id           =       $exam_id;
                                $marks_register->class_id               =       $class_id;
                                $marks_register->section_id             =       $section_id;
                                $marks_register->subject_id             =       $subject_id;
                                $marks_register->student_id             =       $sid;
                                $marks_register->student_record_id      =       $record_id;
                                $marks_register->created_at             = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                $marks_register->total_marks            =       $mark_by_exam_part;
                                $marks_register->exam_setup_id          =       $exam_setup_id;
                                if (isset($absent_students)) {
                                    if (in_array($record_id, $absent_students)) {
                                            $marks_register->is_absent  =       1;
                                    } else {
                                            $marks_register->is_absent  =       0;
                                    }
                                }
        
                                $marks_register->teacher_remarks        =       gv($record, 'teacher_remarks');
        
        
                                    $marks_register->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                    $marks_register->school_id = Auth::user()->school_id;
                                    $marks_register->academic_id = getAcademicId();
        
                                    $marks_register->save();
                                    $marks_register->toArray();
                            } else {
                                    //If already exists, it will updated
                                    $pid = $previous_record->id;
                                    $marks_register = SmMarkStore::find($pid);
                                    $marks_register->total_marks            =       $mark_by_exam_part;
        
                                if (isset($absent_students)) {
                                    if (in_array($record_id, $absent_students)) {
                                            $marks_register->is_absent      =       1;
                                    } else {
                                            $marks_register->is_absent      =       0;
                                    }
                                }
        
                                $marks_register->teacher_remarks          =   gv($record, 'teacher_remarks');
        
                                    $marks_register->save();
                            }
        
        
                                $exam_setup_count++;
                        } // end part insertion
        
                        $subject_full_mark = subjectFullMark($request->exam_id, $request->subject_id, $class_id, $section_id);
                        $student_obtained_mark = $total_marks_persubject;
                        $mark_by_persentage = subjectPercentageMark($student_obtained_mark, $subject_full_mark);
        
                            $mark_grade = SmMarksGrade::where([
                                            ['percent_from', '<=', $mark_by_persentage], 
                                            ['percent_upto', '>=', $mark_by_persentage]])
                                            ->where('academic_id', getAcademicId())
                                            ->where('school_id', Auth::user()->school_id)
                                            ->first();
                            
        
                            $abc[] = $total_marks_persubject;
        
                            $previous_result_record = SmResultStore::where([
                                ['class_id', $class_id],
                                ['section_id', $section_id],
                                ['subject_id', $subject_id],
                                ['exam_type_id', $exam_id],
                                ['student_record_id', $record_id],
                                ['student_id', $sid]
                            ])->first();
        
                        if ($previous_result_record == "" || $previous_result_record == null) {
                            //If not result exists, it will create
                                $result_record = new SmResultStore();
                                $result_record->class_id               =   $class_id;
                                $result_record->section_id             =   $section_id;
                                $result_record->subject_id             =   $subject_id;
                                $result_record->exam_type_id           =   $exam_id;
                                $result_record->student_id             =   $sid;
                                $result_record->student_record_id      =   $record_id;
        
                            if (isset($absent_students)) {
                                if (in_array($record_id, $absent_students)) {
                                        $result_record->is_absent      =       1;
                                } else {
                                        $result_record->is_absent      =       0;
                                }
                            }
                            $result_record->total_marks            =   $total_marks_persubject;
                            $result_record->total_gpa_point        =   @$mark_grade->gpa;
                            $result_record->total_gpa_grade        =   @$mark_grade->grade_name;
        
                            $result_record->teacher_remarks        =   gv($record, 'teacher_remarks');
        
                            $result_record->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            $result_record->school_id = Auth::user()->school_id;
                            $result_record->academic_id = getAcademicId();
                            $result_record->save();
                            $result_record->toArray();
                        } else {                               //If already result exists, it will updated
                                $id = $previous_result_record->id;
                                $result_record = SmResultStore::find($id);
                                $result_record->total_marks            =   $total_marks_persubject;
                                $result_record->total_gpa_point        =   @$mark_grade->gpa;
                                $result_record->total_gpa_grade        =   @$mark_grade->grade_name;
                                $result_record->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            if (isset($absent_students)) {
                                if (in_array($record_id, $absent_students)) {
                                        $result_record->is_absent              =       1;
                                } else {
                                        $result_record->is_absent              =       0;
                                }
                            }
        
                            $result_record->teacher_remarks        =   gv($record, 'teacher_remarks');
        
                            $result_record->save();
                            $result_record->toArray();
                        }
                    }   // If student id is valid

                }
            }//end student loop
                DB::commit();
                Toastr::success('Operation successful', 'Success');
                return redirect('marks-register-create');
        } catch (\Exception $e) {
                DB::rollback();
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
        }
    }
    
    
    public function reportSearch(AddMarkRequest $request)
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

            $exam_type = $request->exam_type;
            $subject_id = $request->subject_id;
            $subjectName = UnSubject::find($subject_id);
    
            

            $examAttendance = SmExamAttendance::query();
            $exam_attendance= universityFilter($examAttendance, $request)
                            ->where('un_subject_id', $subject_id)
                            ->first();

            if ($exam_attendance) {
                $exam_attendance_child = SmExamAttendanceChild::where('exam_attendance_id', $exam_attendance->id)
                ->first();
            } else {
                Toastr::error('Exam attendance Not Done Yet', 'Failed');
                return redirect()->back();
            }
        
            $StudentRecord = StudentRecord::query();
            $students = universityFilter($StudentRecord, $request)->get();

            $SmExamSchedule = SmExamSchedule::query();
            $exam_schedule = universityFilter($SmExamSchedule, $request)
                            ->where('un_subject_id', $subject_id)
                            ->first();
            $request = $request;
            
            if ($students->count() == 0) {
                Toastr::error('Sorry ! Student is not available Or exam schedule is not set yet.', 'Failed');
                return redirect()->back();
            } else {
                $SmExamSetup = SmExamSetup::query();
                $marks_entry_form = universityFilter($SmExamSetup, $request)
                                    ->where('exam_term_id', $exam_type)
                                    ->where('un_subject_id', $subject_id)
                                    ->get();
                $marks_registers = 1;

                if ($marks_entry_form->count() > 0) {
                    $number_of_exam_parts = count($marks_entry_form);
                    return view('backEnd.examination.masks_register', compact(
                        'students', 
                        'number_of_exam_parts', 
                        'marks_entry_form',
                        'marks_registers',
                        'exam_type',
                        'subject_id',
                        'un_session',
                        'un_faculty',
                        'un_department',
                        'un_academic',
                        'un_semester',
                        'un_semester_label',
                        'un_section',
                        'subjectName',
                        'request',
                    ));
                } else {
                    Toastr::error('Sorry ! Exam setup is not set yet.', 'Failed');
                    return redirect()->back();
                }
            }

        }else{
            $exams = SmExam::get();
            $classes = SmClass::get();
            $exam_types = SmExamType::get();
    
            $exam_id = $request->exam;
            $class_id = $request->class;
            $section_id = $request->section !=null ? $request->section : null;
            $subject_id = $request->subject;
            $subjectNames = SmSubject::where('id', $subject_id)->first();
    
            $exam_attendance = SmExamAttendance::query();
            if ($request->class !=null) {
                $exam_attendance->where('exam_id', $exam_id)->where('class_id', $class_id);
            }
            if ($request->section !=null) {
                $exam_attendance->where('section_id', $request->section);
            }
    
            $exam_attendance= $exam_attendance->where('subject_id', $subject_id)->first();

    
            if ($exam_attendance) {
                $exam_attendance_child = SmExamAttendanceChild::where('exam_attendance_id', $exam_attendance->id)->first();
            } else {
                if(!isSkip('exam_attendance')) {
                    Toastr::error('Exam attendance not done yet', 'Failed');
                    return redirect()->back();
                }
            }
        
            $students = StudentRecord::with('class', 'section')
                    ->when($request->academic_year, function ($query) use ($request) {
                        $query->where('academic_id', $request->academic_year);
                    })
                    ->when($request->class, function ($query) use ($request) {
                        $query->where('class_id', $request->class);
                    })
                    ->when($request->section, function ($query) use ($request) {
                        $query->where('section_id', $request->section);
                    })
                    ->when(!$request->academic_year, function ($query) use ($request) {
                        $query->where('academic_id', getAcademicId());
                    })->where('school_id', auth()->user()->school_id)->where('is_promote', 0)->whereHas('studentDetail', function($q){
                            $q->where('active_status', 1);
                        })->get();


                $exam_schedule = SmExamSchedule::where('exam_id', $request->exam)->where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->first();
            if ($students->count() == 0) {
                    Toastr::error('Sorry ! Student is not available Or exam schedule is not set yet.', 'Failed');
                    return redirect()->back();
                    // return redirect()->back()->with('message-danger', 'Sorry ! Student is not available Or exam schedule is not set yet.');
            } else {
                    $marks_entry_form = SmExamSetup::query();
                if ($request->class !=null) {
                        $marks_entry_form->where('exam_term_id', $exam_id)->where('class_id', $class_id);
                }
                if ($request->section !=null) {
                    $marks_entry_form->where('section_id', $request->section);
                }
                $marks_entry_form = $marks_entry_form->where('subject_id', $subject_id)->where('academic_id', getAcademicId())->get();
                

                if ($marks_entry_form->count() > 0) {
                        $number_of_exam_parts = count($marks_entry_form);
                        return view('backEnd.examination.masks_register_search', compact('exams', 'classes', 'students', 'exam_id', 'class_id', 'section_id', 'subject_id', 'subjectNames', 'number_of_exam_parts', 'marks_entry_form', 'exam_types'));
                } else {
                        Toastr::error('Sorry ! Exam setup is not set yet.', 'Failed');
                        return redirect()->back();
                        // return redirect()->back()->with('message-danger', 'Sorry ! Exam schedule is not set yet.');
                }
            }
        }
    } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
    }
    }    
}
