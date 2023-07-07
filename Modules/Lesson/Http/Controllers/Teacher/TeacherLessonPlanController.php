<?php

namespace Modules\Lesson\Http\Controllers\Teacher;

use App\SmAssignSubject;
use App\SmClass;
use App\SmClassTime;
use App\SmStaff;
use App\SmWeekend;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Lesson\Entities\LessonPlanner;
use Modules\Lesson\Entities\SmLesson;
use Modules\Lesson\Entities\SmLessonTopic;

class TeacherLessonPlanController extends Controller
{
    public function teacherLessonPlan(Request $request)
    {

        try {
            $this_week = $weekNumber = date("W");
            $week_end = SmWeekend::where('id',generalSetting()->week_start_id)->value('name');
            $start_day = WEEK_DAYS_BY_NAME[$week_end ?? 'Saturday'];
            $end_day = $start_day == 0 ? 6 : $start_day - 1;
            $period = CarbonPeriod::create(Carbon::now()->startOfWeek($start_day)->format('Y-m-d'), Carbon::now()->endOfWeek($end_day)->format('Y-m-d'));

            $dates = [];
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');

            }

            $login_id = Auth::user()->id;
            $teachers = SmStaff::where('active_status', 1)->where('user_id', $login_id)->where(function($q)  {
                $q->where('role_id', 4)->orWhere('previous_role_id', 4);
            })->where('school_id', Auth::user()->school_id)->first();

            $class_times = SmClassTime::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->orderBy('period', 'ASC')->get();
            $teacher_id = $teachers->id;
            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();

            return view('lesson::teacher.teacherLessonPlan', compact('dates', 'this_week', 'class_times', 'teacher_id', 'sm_weekends', 'teachers'));
        } catch (\Exception$e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }
    public function teacherLessonPlanOverview()
    {

        try {

            $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
            $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)
                ->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                ->where('sm_assign_subjects.academic_id', getAcademicId())
                ->where('sm_assign_subjects.active_status', 1)
                ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                ->select('sm_classes.id', 'class_name')
                ->distinct('sm_classes.id')
                ->get();

            $login_id = Auth::user()->id;
            $teacher = SmStaff::where('active_status', 1)->where('user_id', $login_id)->where(function($q)  {
                $q->where('role_id', 4)->orWhere('previous_role_id', 4);
            })->where('school_id', Auth::user()->school_id)->first();
            $teachers = $teacher->id;

            $lessonPlanDetail = LessonPlanner::where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $lessons = SmLesson::where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();
            $topics = SmLessonTopic::where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()
                        ->school_id)
                    ->get();

            return view('lesson::teacher.teacher_lesson_plan_overview', compact('lessonPlanDetail', 'lessons', 'topics', 'classes', 'teachers'));
        } catch (\Exception$e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function searchTeacherLessonPlanOverview(Request $request)
    {

        if (moduleStatusCheck('University')) {
            $request->validate([
                
                'un_session_id' => 'sometimes|nullable',
                'un_faculty_id' => 'sometimes|nullable',
                'un_department_id' => 'sometimes|nullable',
                'un_academic_id' => 'sometimes|nullable',
                'un_semester_id' => 'sometimes|nullable',
                'un_semester_label_id' => 'sometimes|nullable',
                'un_subject_id' => 'sometimes|nullable',
            ]);
        } else {
            $request->validate([
                'class' => 'required',
                'section' => 'required',
                'subject' => 'required',

            ]);
        }
        try {

            if (moduleStatusCheck('University')) {
                $total = LessonPlanner::where('teacher_id', $request->teacher)
                ->where('un_semester_label_id', $request->un_semester_label_id)
                ->where('un_subject_id', $request->un_subject_id)
                ->get()->count();
                $completed_total = LessonPlanner::where('completed_status', 'completed')
                ->where('teacher_id', $request->teacher)
                ->where('un_semester_label_id', $request->un_semester_label_id)
                ->where('un_subject_id', $request->un_subject_id)
                ->get()
                ->count();

                if ($total > 0) {
                    $percentage = $completed_total / $total * 100;
                } else {
                    $percentage = 0;
                }
             

                $lessonPlanner = LessonPlanner::where('teacher_id', $request->teacher)
                    ->where('un_semester_label_id', $request->un_semester_label_id)
                    ->where('un_subject_id', $request->un_subject_id)
                    ->distinct('lesson_detail_id')
                    ->get();

                $alllessonPlanner = LessonPlanner::where('teacher_id', $request->teacher)
                    ->where('un_semester_label_id', $request->un_semester_label_id)
                    ->where('un_subject_id', $request->un_subject_id)
                    ->where('subject_id', $request->subject)
                    ->get();
            } else {
                $total = LessonPlanner::lessonPlanner($request->teacher, $request->class, $request->section, $request->subject)->count();
                $completed_total = LessonPlanner::lessonPlanner($request->teacher, $request->class, $request->section, $request->subject)->where('completed_status', 'completed')->count();
                if ($total > 0) {
                    $percentage = $completed_total / $total * 100;
                } else {
                    $percentage = 0;
                }
                if ($request->teacher != "" && $request->class != "" && $request->section != "" && $request->subject != "") {
                    $lessonPlanner = LessonPlanner::lessonPlanner($request->teacher, $request->class, $request->section, $request->subject)->distinct('lesson_detail_id')->get();
                    $alllessonPlanner = LessonPlanner::lessonPlanner($request->teacher, $request->class, $request->section, $request->subject)->get();

                }
            }
            $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $teachers = $request->teacher;
            return view('lesson::teacher.teacher_lesson_plan_overview', compact('total', 'completed_total', 'alllessonPlanner', 'lessonPlanner', 'classes', 'teachers', 'percentage'));

        } catch (\Exception$e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function changeWeek($next_date)
    {
        try {
            $start_date = Carbon::parse($next_date)->addDay(1);
            $date = Carbon::parse($next_date)->addDay(1);

            $end_date = Carbon::parse($start_date)->addDay(7);
            $this_week = $week_number = $end_date->weekOfYear;

            $period = CarbonPeriod::create($start_date, $end_date);

            $dates = [];
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');

            }

            $login_id = Auth::user()->id;
            $teachers = SmStaff::where('active_status', 1)->where('user_id', $login_id)->where(function($q)  {
	            $q->where('role_id', 4)->orWhere('previous_role_id', 4);
            })->where('school_id', Auth::user()->school_id)->first();

            $user = Auth::user();
            $class_times = SmClassTime::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->orderBy('period', 'ASC')->get();
            $teacher_id = $teachers->id;
            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();

            return view('lesson::teacher.teacherLessonPlan', compact('dates', 'this_week', 'class_times', 'teacher_id', 'sm_weekends', 'teachers'));

        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function discreaseChangeWeek($pre_date)
    {
        try {
            $end_date = Carbon::parse($pre_date)->subDays(1);
            $start_date = Carbon::parse($end_date)->subDays(6);

            $this_week = $week_number = $end_date->weekOfYear;

            $period = CarbonPeriod::create($start_date, $end_date);

            $dates = [];
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');

            }

            $login_id = Auth::user()->id;
            $teachers = SmStaff::where('active_status', 1)->where('user_id', $login_id)->where(function($q)  {
                $q->where('role_id', 4)->orWhere('previous_role_id', 4);
            })->where('school_id', Auth::user()->school_id)->first();

            $user = Auth::user();
            $class_times = SmClassTime::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->orderBy('period', 'ASC')->get();
            $teacher_id = $teachers->id;
            $sm_weekends = SmWeekend::where('school_id', Auth::user()->school_id)->orderBy('order', 'ASC')->where('active_status', 1)->get();

            return view('lesson::teacher.teacherLessonPlan', compact('dates', 'this_week', 'class_times', 'teacher_id', 'sm_weekends', 'teachers'));
        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

}
