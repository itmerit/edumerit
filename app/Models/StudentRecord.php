<?php

namespace App\Models;

use App\SmExam;
use App\SmClass;
use App\SmExamSchedule;
use App\SmExamType;
use App\SmHomework;
use App\SmFeesAssign;
use App\SmOnlineExam;
use App\SmResultStore;
use App\SmAssignSubject;
use App\SmStudentAttendance;
use App\SmFeesAssignDiscount;
use App\SmClassOptionalSubject;
use App\SmTeacherUploadContent;
use App\SmStudentTakeOnlineExam;
use Modules\Lms\Entities\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\FeesInstallmentCredit;
use Illuminate\Database\Eloquent\Model;
use Modules\Zoom\Entities\VirtualClass;
use Modules\ExamPlan\Entities\AdmitCard;
use Modules\Fees\Entities\FmFeesInvoice;
use App\Scopes\StatusAcademicSchoolScope;
use Modules\BBB\Entities\BbbVirtualClass;
use Modules\Lesson\Entities\LessonPlanner;
use Modules\University\Entities\UnSubject;
use Modules\Gmeet\Entities\GmeetVirtualClass;
use Modules\Jitsi\Entities\JitsiVirtualClass;
use Modules\OnlineExam\Entities\InfixWrittenExam;
use Modules\OnlineExam\Entities\InfixOnlineExam;
use Modules\University\Entities\UnAssignSubject;
use Modules\University\Entities\UnSubjectComplete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Modules\University\Entities\UnSubjectPreRequisite;
use Modules\University\Entities\UnSubjectAssignStudent;
use Modules\OnlineExam\Entities\InfixStudentTakeOnlineExam;

class StudentRecord extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];

    

    public function class()
    {
        return $this->belongsTo('App\SmClass', 'class_id', 'id')->withDefault()->withoutGlobalScope(StatusAcademicSchoolScope::class);
    }

    public function admitcard()
    {
        return $this->belongsTo(AdmitCard::class,'student_record_id');
    }

    public function section()
    {
        if(moduleStatusCheck('University')){
            return $this->belongsTo('App\SmSection', 'un_section_id', 'id')->withDefault()->withoutGlobalScope(StatusAcademicSchoolScope::class);
        }else{
            return $this->belongsTo('App\SmSection', 'section_id', 'id')->withDefault()->withoutGlobalScope(StatusAcademicSchoolScope::class);
        }

    }

    public function unSection()
    {
        return $this->belongsTo('App\SmSection', 'un_section_id', 'id')->withDefault()->withoutGlobalScope(StatusAcademicSchoolScope::class);
    }

    public function student()
    {
        return $this->hasOne('App\SmStudent', 'id', 'student_id');
    }
    public function school()
    {
        return $this->belongsTo('App\SmSchool', 'school_id', 'id')->withDefault();
    }
    public function academic()
    {
        return $this->belongsTo('App\SmAcademicYear', 'academic_id', 'id')->withDefault();
    }
    public function classes()
    {
        return $this->hasMany(SmClass::class, 'academic_id', 'academic_id');
    }
    
    public function studentDetail()
    {
        return $this->belongsTo('App\SmStudent', 'student_id', 'id')->withDefault();
    }

    public function fees()
    {
        return $this->hasMany(SmFeesAssign::class, 'record_id', 'id');
    }

    public function feesDiscounts()
    {
        return $this->hasMany(SmFeesAssignDiscount::class, 'record_id', 'id');
    }

    public function homework()
    {
        return $this->hasMany(SmHomework::class, 'record_id', 'id')->whereNull('course_id')->whereNull('chapter_id')->whereNull('lesson_id');
    }

    public function studentAttendance()
    {
        return $this->hasMany(SmStudentAttendance::class, 'student_record_id', 'id');
    }

    public function studentAttendanceByMonth($month, $year)
    {
        return $this->studentAttendance()->where('attendance_date', 'like', $year . '-' . $month . '%')->get();
    }

    public function getLessonPlanAttribute()
    {
        return LessonPlanner::where('class_id', $this->class_id)
        ->where('section_id', $this->section_id)
        ->where('active_status', 1)
        ->distinct('lesson_detail_id')
        ->get(); 

    }
    public function getHomeWorkAttribute()
    {
        return SmHomework::with('classes', 'sections', 'subjects')->where('class_id', $this->class_id)->where('section_id', $this->section_id)
        ->whereNull('course_id')
        ->where('sm_homeworks.academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
    }

    public function getUploadContent($type, $is_university = null)
    {
        if($is_university == null){
            $class = $this->class_id;
            $section = $this->section_id;
            $content = [];
                $content = SmTeacherUploadContent::where('content_type', $type)
                ->where(function ($que) use ($class) {
                    return $que->where('class', $class)
                    ->orWhereNull('class');
                })
                ->where(function ($que) use ($section) {
                    return $que->where('section', $section)
                    ->orWhereNull('section');
                })
                ->where('course_id', '=', null)
                ->where('chapter_id', '=', null)
                ->where('lesson_id', '=', null)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

                return $content;
        }else{
            $un_semester_label_id = $this->un_semester_label_id;
            $section_id = $this->un_section_id;
            $content = [];
            $content = SmTeacherUploadContent::where('content_type', $type)
            ->where(function ($que) use ($un_semester_label_id) {
                return $que->where('un_semester_label_id', $un_semester_label_id);
            })
            ->where(function ($que) use ($section_id) {
                return $que->where('un_section_id', $section_id);
            })
            ->where('course_id', '=', null)
            ->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)
            ->get();

            return $content;
        }

    }


    public function homeworkContents($is_university = null)
    {
        if($is_university == null){
            $class = $this->class_id;
            $section = $this->section_id;
            $content = [];
                $content = SmHomework::where('school_id', auth()->user()->school_id)
                ->where(function ($que) use ($class) {
                    return $que->where('class_id', $class)
                    ->orWhereNull('class_id');
                })
                ->where(function ($que) use ($section) {
                    return $que->where('section_id', $section)
                    ->orWhereNull('section_id');
                })
                ->where('course_id', '=', null)
                ->where('chapter_id', '=', null)
                ->where('lesson_id', '=', null)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

                return $content;
        }else{
            $un_semester_label_id = $this->un_semester_label_id;
            $section_id = $this->un_section_id;
            $content = [];
            $content = SmHomework::where('school_id', auth()->user()->school_id)
            ->where(function ($que) use ($un_semester_label_id) {
                return $que->where('un_semester_label_id', $un_semester_label_id);
            })
            ->where(function ($que) use ($section_id) {
                return $que->where('un_section_id', $section_id);
            })
            ->where('course_id', '=', null)
            ->where('un_academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)
            ->get();

            return $content;
        }

    }

    public function getExamAttribute()
    {
       return SmExam::with('examType')->where('class_id',$this->class_id)->where('section_id',$this->section_id)->where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->where('active_status', 1)->get();
    }

    public function getAssignSubjectAttribute()
    {
       return SmAssignSubject::where('class_id', $this->class_id)->where('section_id', $this->section_id)->where('academic_id', $this->academic_id)->where('school_id', Auth::user()->school_id)->get();
    }

    public function getUnAssignSubjectAttribute()
    {
       return UnAssignSubject::where('un_semester_label_id', $this->un_semester_label_id)->where('school_id', Auth::user()->school_id)->get();
    }

    public function getOnlineExamAttribute()
    {
        $subjectIds = SmAssignSubject::where('class_id', $this->class_id)
        ->where('section_id', $this->section_id)->where('school_id', Auth::user()->school_id)
        ->where('academic_id', getAcademicId())
        ->pluck('subject_id')->unique();
        if (moduleStatusCheck('OnlineExam')==true) {
            if (moduleStatusCheck('University')) {
                return InfixOnlineExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('status', 1)
                ->where('un_faculty_id', $this->un_faculty_id)
                ->where('un_department_id', $this->un_department_id)
                ->where('un_semester_label_id', $this->un_semester_label_id)
                ->where('school_id', Auth::user()->school_id)
                ->get();
            }
            return InfixOnlineExam::with('studentSubmitExamWithStatus')->where('active_status', 1)->where('academic_id', getAcademicId())->where('status', 1)->where('class_id', $this->class_id)
            ->where('school_id', Auth::user()->school_id)
            ->get()->filter(function ($exam) {
                $exam->when($exam->section_id, function ($q) {
                    $q->where('section_id', $this->section_id);
                });
                return $exam;
            });
        }
        return SmOnlineExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('status', 1)->where('class_id', $this->class_id)->where('section_id', $this->section_id)->where('school_id', Auth::user()->school_id)->get();
    }
    public function getInfixStudentTakeOnlineExamAttribute()
    {
        if (moduleStatusCheck('OnlineExam')==true && auth()->user()->role_id==2) {
          return  InfixStudentTakeOnlineExam::where('status', 2)
            ->where('student_id', auth()->user()->student->id)
            ->whereHas('onlineExam', function ($query) {
            return    $query->when(moduleStatusCheck('Lms'), function ($q) {
                    $q->whereNull('course_id');
                });
            })->where('student_record_id', $this->id)->get();
        }
    }
    public static function getInfixStudentTakeOnlineExamParent($student_id, $record_id)
    {
        if(moduleStatusCheck('OnlineExam')==true) {
           return InfixStudentTakeOnlineExam::where('status', 2)
            ->where('student_id', $student_id)
            ->where('student_record_id', $record_id)->get();
        } else {
          return  SmStudentTakeOnlineExam::
                    where('active_status', 1)->where('status', 2)
                    ->where('academic_id', getAcademicId())
                    ->where('student_id', $student_id)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
        }           

        
    }
    public function getStudentTeacherAttribute()
    {
        return SmAssignSubject::with('teacher')->select('teacher_id')->where('class_id', $this->class_id)
        ->where('section_id', $this->section_id)->distinct('teacher_id')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
    }
    public function getStudentVirtualClassAttribute()
    {
        return VirtualClass::where('class_id', $this->class_id)
        ->where(function ($q) {
            return $q->where('section_id', $this->section_id)->orWhereNull('section_id');
        })
        ->where('school_id', Auth::user()->school_id)
        ->get();
    }


    public function getUnstudentVirtualClassAttribute()
    {
        return VirtualClass::where('un_semester_label_id', $this->un_semester_label_id)
        ->where(function ($q) {
            return $q->where('un_section_id', $this->un_section_id)->orWhereNull('un_section_id');
        })
        ->where('school_id', Auth::user()->school_id)
        ->get();
    }

    public function getStudentBbbVirtualClassAttribute()
    {
        return BbbVirtualClass::where('class_id', $this->class_id)
        ->where(function ($q) {
            return $q->where('section_id', $this->section_id)->orWhereNull('section_id');
        })
        // ->where('school_id', Auth::user()->school_id)
        ->get();
    }
    public function getStudentBbbVirtualClassRecordAttribute()
    {
        $meetings = BbbVirtualClass::where('class_id', $this->class_id)
        ->where(function ($q) {
            return $q->where('section_id', $this->section_id)->orWhereNull('section_id');
        })
        // ->where('school_id', Auth::user()->school_id)
        ->get();
        $meeting_id = $meetings->pluck('meeting_id')->toArray();
        $recordList = Bigbluebutton::getRecordings(['meetingID' => $meeting_id]);
        return $recordList;
    }
    public function getStudentJitsiVirtualClassAttribute()
    {
        return JitsiVirtualClass::where('class_id', $this->class_id)
        ->where(function ($q) {
            return $q->where('section_id', $this->section_id)->orWhereNull('section_id');
        })
        ->get();
    }
    public function getStudentGmeetVirtualClassAttribute()
    {
        return GmeetVirtualClass::where('class_id', $this->class_id)
        ->where(function ($q) {
            return $q->where('section_id', $this->section_id)->orWhereNull('section_id');
        })
        ->get();
    }
    public function getOnlineWrittenExamAttribute()
    {
        if (moduleStatusCheck('University')) {
            return InfixWrittenExam::where('active_status', 1)->where('un_academic_id', getAcademicId())->where('status', 1)->where('un_semester_label_id', $this->un_semester_label_id)->where('school_id', Auth::user()->school_id)->get();
        } else {
            return InfixWrittenExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('status', 1)->where('class_id', $this->class_id)->where('section_id', $this->section_id)->where('school_id', Auth::user()->school_id)->with('class', 'section', 'subject')->get();
        }
 
    }
    
    public function getStudentCoursesAttribute()
    {
        return Course::where(function ($q) {
            return $q->where('class_id', $this->class_id)->orWhere('class_id', 0);
        })->where( function ($q) {
            return $q->where('section_id', $this->section_id)->orWhere('section_id', null);

        })->withCount('chapters', 'lessons')->where('active_status', 1)->where('publish',1)->get();
    }
    public function feesInvoice()
    {
        return $this->hasMany('Modules\Fees\Entities\FmFeesInvoice', 'record_id', 'id');
    }
    public function unSession()
    {
        return $this->belongsTo('Modules\University\Entities\UnSession', 'un_session_id', 'id')->withDefault();
    }
    public function unFaculty()
    {
        return $this->belongsTo('Modules\University\Entities\UnFaculty', 'un_faculty_id', 'id')->withDefault();
    }
    public function unDepartment()
    {
        return $this->belongsTo('Modules\University\Entities\UnDepartment', 'un_department_id', 'id')->withDefault();
    }
    public function unAcademic()
    {
        return $this->belongsTo('Modules\University\Entities\UnAcademicYear', 'un_academic_id', 'id')->withDefault();
    }
    public function unSemester()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemester', 'un_semester_id', 'id')->withDefault();
    }
    public function unSemesterLabel()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemesterLabel', 'un_semester_label_id', 'id')->withDefault();
    }
    public function semester()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemester', 'un_semester_id', 'id')->withDefault();
    }

    public function semesterLabel()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemesterLabel', 'un_semester_label_id', 'id')->withDefault();
    }

    public function markStoreDetails()
    {
        return $this->belongsTo('App\SmMarkStore', 'student_record_id', 'id')->withDefault();
    }
    public function unStudentSubjects()
    {
        return $this->hasMany('Modules\University\Entities\UnSubjectAssignStudent', 'student_record_id', 'id');
    }
    public function unStudentSemesterWiseSubjects()
    {
        return $this->hasMany('Modules\University\Entities\UnSubjectAssignStudent', 'student_record_id', 'id')
        ->where('un_semester_label_id', $this->un_semester_label_id)
        ->orderby('un_semester_label_id', 'DESC');
    }

    public function unStudentRequestSubjects()
    {
        return $this->hasMany('Modules\University\Entities\RequestSubject', 'student_record_id', 'id')
        ->where('un_semester_label_id', $this->un_semester_label_id)
        ->orderby('un_semester_label_id', 'DESC');
    }

    public function feesInstallments()
    {
        return $this->hasMany('Modules\University\Entities\UnFeesInstallmentAssign', 'record_id', 'id');
    }

    public function directFeesInstallments()
    {
        return $this->hasMany(DirectFeesInstallmentAssign::class, 'record_id', 'id');
    }

    public function getWithOutPreSubjectAttribute()
    {
        $preSubjectIds = UnSubjectPreRequisite::pluck('un_subject_id')->toArray();
        // $assignSubjects = UnAssignSubject::where('un_semester_label_id', $this->un_semester_label_id)
        //                 ->pluck('un_subject_id')
        //                 ->toArray();
        $assignSubjects = [];

        $studentAssignSubjects = UnSubjectAssignStudent::where('un_semester_label_id', $this->un_semester_label_id)
                        ->where('student_id', $this->student_id)
                        ->where('student_record_id', $this->id)
                        ->pluck('un_subject_id')
                        ->toArray();

       $completeSubjects = UnSubjectComplete::where('student_id', $this->student_id)
                        // ->where('is_pass', '!=', 'pass')
                        ->pluck('un_subject_id')->toArray();
        $array = array_unique(array_merge($preSubjectIds, $assignSubjects, $completeSubjects));

        return UnSubject::where('un_faculty_id', $this->un_faculty_id)
                            ->where('un_department_id', $this->un_department_id)
                            ->whereNotIn('id', $array)
                            ->where('school_id', auth()->user()->school_id)
                            ->orWhereNull('un_department_id')
                            ->orWhereNull('un_faculty_id')
                            ->get();
    }

    public function getStudentNameAttribute()
    {
        return  $this->studentDetail ? $this->studentDetail->full_name : '';
    }

    public function getRollNoAttribute($value){
        if(generalSetting()->multiple_roll){
            return $value;
        }

        $this->load('studentDetail');


        return $this->studentDetail->roll_no;

    }
    public function credit()
    {
        return $this->hasOne('App\Models\FeesInstallmentCredit','student_record_id');
    }

    public function alumni()
    {
        return $this->hasOne('Modules\Alumni\Entities\Alumni', 'record_id');
    }

    public function examSchedule()
    {
        return $this->hasMany(SmExamSchedule::class, 'class_id', 'class_id')->where('section_id', $this->section_id);
    }

    

}
