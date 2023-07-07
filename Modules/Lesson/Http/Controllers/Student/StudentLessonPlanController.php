<?php

namespace Modules\Lesson\Http\Controllers\Student;
use App\SmClass;
use App\SmStaff;
use App\SmLesson;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmWeekend;
use Carbon\Carbon;
use App\SmClassTime;
use App\ApiBaseMethod;
use App\SmLessonTopic;
use App\SmLessonDetails;
use Carbon\CarbonPeriod;
use App\SmLessonTopicDetail;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Lesson\Entities\LessonPlanner;
use Illuminate\Contracts\Support\Renderable;

class StudentLessonPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request,$id=null)
    {
        try {
          
            $this_week= $weekNumber = date("W");            
            $week_end = SmWeekend::where('id',generalSetting()->week_start_id)->value('name');
            $start_day = WEEK_DAYS_BY_NAME[$week_end ?? 'Saturday'];
            $end_day = $start_day==0 ? 6 : $start_day-1;
            $period = CarbonPeriod::create(Carbon::now()->startOfWeek($start_day)->format('Y-m-d'), Carbon::now()->endOfWeek($end_day)->format('Y-m-d'));
            $dates=[];
            foreach ($period as $date) {
                    $dates[] = $date->format('Y-m-d');
                 
             }
            $student_detail = SmStudent::where('user_id', auth()->user()->id)->first();
            //return $student_detail;
            $class_id = $student_detail->class_id;
            $section_id = $student_detail->section_id;

            $records = studentRecords(null, $student_detail->id)->get();

            $sm_weekends = SmWeekend::orderBy('order', 'ASC')->where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            return view('lesson::student.student_lesson_plan', compact('dates','this_week','class_id','section_id', 'sm_weekends','records'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
        // return view('lesson::index');
    }
    public function overview(Request $request,$id = null){

       try{ 
           
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $login_id = $id;
        } else {
            $login_id = Auth::user()->id;
        }
        $student_detail = SmStudent::where('user_id', $login_id)->first();
        $class=$student_detail->class_id;
        $section=$student_detail->section_id;       
        $academic_id=$student_detail->academic_id;
        $school_id=$student_detail->school_id;

        $records = studentRecords(null, $student_detail->id)->get();         
        $classes = SmClass::get();

        return view('lesson::student.student_lesson_plan_overview',compact('classes', 'records'));

       }catch(\Exception $e){
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
       }
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function changeWeek(Request $request,$next_date)
    {
            $start_date=Carbon::parse($next_date)->addDay(1);
            $date = Carbon::parse($next_date)->addDay(1);       
            $end_date=Carbon::parse($start_date)->addDay(7);
            $this_week= $week_number = $end_date->weekOfYear;
            $period = CarbonPeriod::create($start_date, $end_date);
            $dates=[];
            foreach ($period as $date){
                    $dates[] = $date->format('Y-m-d');
             }
          
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $user_id = $id;
            } else {
                $user = Auth::user();

                if ($user) {
                    $user_id = $user->id;
                } else {
                    $user_id = $request->user_id;
                }
            }

            $student_detail = SmStudent::where('user_id', $user_id)->first();
            //return $student_detail;
            $class_id = $student_detail->class_id;
            $section_id = $student_detail->section_id;

            $sm_weekends = SmWeekend::orderBy('order', 'ASC')->where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();

            $class_times = SmClassTime::where('type', 'class')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $records = studentRecords(null, $student_detail->id)->get();
            return view('lesson::student.student_lesson_plan', compact('dates','this_week','class_times', 'class_id', 'section_id', 'sm_weekends','records'));
      
    }
    public function discreaseChangeWeek(Request $request,$pre_date)
    {
 

        $end_date=Carbon::parse($pre_date)->subDays(1);     
       
        $start_date=Carbon::parse($end_date)->subDays(6);
     
        $this_week= $week_number = $end_date->weekOfYear;
        ;
        $period = CarbonPeriod::create($start_date, $end_date);
        

        $dates=[];
        foreach ($period as $date){
                $dates[] = $date->format('Y-m-d');
             
         }
           
          if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $user_id = $id;
            } else {
                $user = Auth::user();

                if ($user) {
                    $user_id = $user->id;
                } else {
                    $user_id = $request->user_id;
                }
            }

            $student_detail = SmStudent::where('user_id', $user_id)->first();
            //return $student_detail;
            $class_id = $student_detail->class_id;
            $section_id = $student_detail->section_id;

            $sm_weekends = SmWeekend::orderBy('order', 'ASC')->where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();

            $class_times = SmClassTime::where('type', 'class')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $records = studentRecords(null, $student_detail->id)->get();

            return view('lesson::student.student_lesson_plan', compact('dates','this_week','class_times', 'class_id', 'section_id', 'sm_weekends','records'));
    }
    public function create()
    {
        return view('lesson::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('lesson::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('lesson::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
