<?php

namespace Modules\Lesson\Http\Controllers;
use DataTables;
use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmSubject;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Lesson\Entities\SmLesson;
use Illuminate\Support\Facades\Config;
use Modules\Lesson\Entities\LessonPlanner;
use Modules\Lesson\Entities\SmLessonTopic;
use Modules\University\Entities\UnSubject;
use Modules\Lesson\Entities\SmLessonTopicDetail;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;

class SmLessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }
    public function index()
    {
        try {
            $data = $this->loadLesson();
            return view('lesson::lesson.add_new_lesson', $data);
        } catch (\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function storeLesson(Request $request)
    {
        if (moduleStatusCheck('University')) {
            $request->validate(
                [
                    'un_session_id' => 'required',
                    'un_faculty_id' => 'sometimes|nullable',
                    'un_department_id' => 'required',
                    'un_academic_id' => 'required',
                    'un_semester_id' => 'required',
                    'un_semester_label_id' => 'required',
                    'un_subject_id' => 'required',
                    'un_section_id' => 'sometimes|nullable',
                ],
            );
        } else {
            $request->validate(
                [
                    'class' => 'required',
                    'subject' => 'required',
                ],
            );
        }

        DB::beginTransaction();
        try {
            $sections = SmAssignSubject::where('class_id', $request->class)
                ->where('subject_id', $request->subject)
                ->get();
            if (moduleStatusCheck('University')) {
                if ($request->un_section_id) {
                    $sections = UnSubject::where('un_department_id', $request->un_department_id)
                    ->where('school_id', auth()->user()->school_id)
                    ->get();
                } else {
                    $sections = $request->un_section_id;
                }
            }
            foreach ($sections as $section) {
                foreach ($request->lesson as $lesson) {
                    $smLesson = new SmLesson;
                    $smLesson->lesson_title = $lesson;
                    $smLesson->class_id = $request->class;
                    $smLesson->subject_id = $request->subject;
                    $smLesson->section_id = $section->section_id;
                    $smLesson->school_id = auth()->user()->school_id;
                    $smLesson->user_id = auth()->user()->id;
                    if (moduleStatusCheck('University')) {
                        $common = App::make(UnCommonRepositoryInterface::class);
                        $common->storeUniversityData($smLesson, $request);
                    }else{
                        $smLesson->academic_id = getAcademicId();
                    }
                    $smLesson->save();
                }
            }
            DB::commit();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editLesson($class_id, $section_id, $subject_id)
    {
        try {
            $data = $this->loadLesson();
            $data['lesson'] = SmLesson::where([['class_id', $class_id], ['section_id', $section_id], ['subject_id', $subject_id]])->first();
            $data['lesson_detail'] = SmLesson::where([['class_id', $class_id], ['section_id', $section_id], ['subject_id', $subject_id]])->get();
            return view('lesson::lesson.edit_lesson', $data);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editLessonForUniVersity($session_id, $faculty_id = null, $department_id, $academic_id, $semester_id, $semester_label_id, $subject_id)
    {
        try {
            $data = $this->loadLesson();
            $lesson = SmLesson::when($session_id, function ($query) use ($session_id) {
                $query->where('un_session_id', $session_id);
            })->when($faculty_id !=0, function ($query) use ($faculty_id) {
                $query->where('un_faculty_id', $faculty_id);
            })->when($department_id, function ($query) use ($department_id) {
                $query->where('un_department_id', $department_id);
            })->when($academic_id, function ($query) use ($academic_id) {
                $query->where('un_academic_id', $academic_id);
            })->when($semester_id, function ($query) use ($semester_id) {
                $query->where('un_semester_id', $semester_id);
            })->when($semester_label_id, function ($query) use ($semester_label_id) {
                $query->where('un_semester_label_id', $semester_label_id);
            })->when($subject_id !=0, function ($query) use ($subject_id) {
                $query->where('un_subject_id', $subject_id);
            });
            $data['lesson_detail'] = $lesson->get();
            $data['lesson'] = $lesson->first();
            $interface = App::make(UnCommonRepositoryInterface::class);
            $data += $interface->getCommonData($data['lesson']);
            return view('lesson::lesson.edit_lesson', $data);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateLesson(Request $request)
    {
        try {
            $length = count($request->lesson);
            for ($i = 0; $i < $length; $i++) {
                $lessonDetail = SmLesson::find($request->lesson_detail_id[$i]);
                $lesson_title = $request->lesson[$i];
                $lessonDetail->lesson_title = $lesson_title;
                $lessonDetail->school_id = Auth::user()->school_id;
                $lessonDetail->academic_id = getAcademicId();
                $lessonDetail->user_id = Auth::user()->id;
                $lessonDetail->save();
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('lesson');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteLesson($id)
    {
        $lesson = SmLesson::find($id);
        $lesson_detail = SmLesson::where([['class_id', $lesson->class_id], ['section_id', $lesson->section_id], ['subject_id', $lesson->subject_id]])->get();
        foreach ($lesson_detail as $lesson_data) {
            SmLesson::destroy($lesson_data->id);
        }
        $SmLessonTopic = SmLessonTopic::where('lesson_id', $id)->get();
        if ($SmLessonTopic) {
            foreach ($SmLessonTopic as $t_data) {
                SmLessonTopic::destroy($t_data->id);
            }
        }
        $SmLessonTopicDetail = SmLessonTopicDetail::where('lesson_id', $id)->get();
        if ($SmLessonTopicDetail) {
            foreach ($SmLessonTopicDetail as $td_data) {
                SmLessonTopicDetail::destroy($td_data->id);
            }
        }
        $LessonPlanner = LessonPlanner::where('lesson_id', $id)->get();
        if ($LessonPlanner) {
            foreach ($LessonPlanner as $lp_data) {
                LessonPlanner::destroy($lp_data->id);
            }
        }
        Toastr::success('Operation successful', 'Success');
        return redirect()->route('lesson');
    }

    public function destroyLesson(Request $request)
    {   $id = $request->id;
       return $lesson = SmLesson::find($id);
        $lesson_detail = SmLesson::where([['class_id', $lesson->class_id], ['section_id', $lesson->section_id], ['subject_id', $lesson->subject_id]])->get();
        foreach ($lesson_detail as $lesson_data) {
            SmLesson::destroy($lesson_data->id);
        }
        $SmLessonTopic = SmLessonTopic::where('lesson_id', $id)->get();
        if ($SmLessonTopic) {
            foreach ($SmLessonTopic as $t_data) {
                SmLessonTopic::destroy($t_data->id);
            }
        }
        $SmLessonTopicDetail = SmLessonTopicDetail::where('lesson_id', $id)->get();
        if ($SmLessonTopicDetail) {
            foreach ($SmLessonTopicDetail as $td_data) {
                SmLessonTopicDetail::destroy($td_data->id);
            }
        }
        $LessonPlanner = LessonPlanner::where('lesson_id', $id)->get();
        if ($LessonPlanner) {
            foreach ($LessonPlanner as $lp_data) {
                LessonPlanner::destroy($lp_data->id);
            }
        }
        Toastr::success('Operation successful', 'Success');
        return redirect()->route('lesson');
    }

    public function deleteLessonItem($id)
    {
        try {
            $lesson = SmLesson::find($id);
            $lesson->delete();
            $SmLessonTopic = SmLessonTopic::where('lesson_id', $id)->get();
            if ($SmLessonTopic) {
                foreach ($SmLessonTopic as $t_data) {
                    SmLessonTopic::destroy($t_data->id);
                }
            }
            $SmLessonTopicDetail = SmLessonTopicDetail::where('lesson_id', $id)->get();
            if ($SmLessonTopicDetail) {
                foreach ($SmLessonTopicDetail as $td_data) {
                    SmLessonTopicDetail::destroy($td_data->id);
                }
            }
            $LessonPlanner = LessonPlanner::where('lesson_id', $id)->get();
            if ($LessonPlanner) {
                foreach ($LessonPlanner as $lp_data) {
                    LessonPlanner::destroy($lp_data->id);
                }
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->route('lesson');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function lessonPlanner()
    {
        return view('lesson::lesson.lesson_planner');
    }

    public function loadLesson()
    {
        $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
        $subjects = SmAssignSubject::select('subject_id')
        ->where('teacher_id', $teacher_info->id)->get();

        $data['subjects'] = SmSubject::where('active_status', 1)
        ->where('academic_id', getAcademicId())
        ->where('school_id', Auth::user()->school_id)->get();
        $data['sections'] = SmSection::where('active_status', 1)
        ->where('academic_id', getAcademicId())
        ->where('school_id', Auth::user()->school_id)->get();

        if (Auth::user()->role_id == 4) {
            $data['lessons'] = SmLesson::with('lessons', 'class', 'section', 'subject')
            ->whereIn('subject_id', $subjects)->statusCheck()
            ->get();
        } else {
            $data['lessons'] = SmLesson::with('lessons', 'class', 'section', 'subject')
                ->statusCheck()
                ->get();
        }
        if (!teacherAccess()) {
            $data['classes'] = SmClass::where('active_status', 1)
            ->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)->get();
        } else {
            $data['classes'] = SmAssignSubject::where('teacher_id', $teacher_info->id)
                ->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                ->where('sm_assign_subjects.academic_id', getAcademicId())
                ->where('sm_assign_subjects.active_status', 1)
                ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                ->select('sm_classes.id', 'class_name')
                ->get();
        }
        return $data;
    }


    public function lessonListAjax(Request $request)
    {
        if(!$request->ajax()){
            if (Auth::user()->role_id == 4) {
                $lessons = SmLesson::with('lessons', 'class', 'section', 'subject')
                ->whereIn('subject_id', $subjects)->statusCheck()
                ->get();
            } else {
                $lessons = SmLesson::with('lessons', 'class', 'section', 'subject')
                    ->statusCheck()
                    ->get();
            }
            return Datatables::of($lessons)
            ->addIndexColumn()
            ->addColumn('lesson_name', function ($row){
                $lesson_name = "";
                $lesson_title = SmLesson::lessonName($row->class_id, $row->section_id, $row->subject_id);
                foreach($lesson_title as $key=> $data){
                        $lesson_name.=  $data->lesson_title;
                        if($lesson_title->last() != $data){
                            $lesson_name.= ',';
                        }
                }
                return $lesson_name;
            })
            ->addColumn('action', function ($row) {
                if(moduleStatusCheck('University')){
                    $btn = '<div class="dropdown CRM_dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                    <div class="dropdown-menu dropdown-menu-right">' .
                    (userPermission('un-lesson-edit') === true ? '<a class="dropdown-item" href="' . route('lesson-edit', [$row->un_session_id, $row->un_faculty_id ?? 0, $row->un_department_id, $row->un_academic_id, $row->un_semester_id, $row->un_semester_label_id, $row->un_subject_id ?? 0]) . '">' . app('translator')->get('common.edit') . '</a>' : '').
                    (userPermission('lesson-delete') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a onclick="deleteLesson(' . $row->id . ');"  class="dropdown-item" href="#" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .
                    '</div>
                </div>';
                }else{
                    $btn = '<div class="dropdown CRM_dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                    <div class="dropdown-menu dropdown-menu-right">' .
                    (userPermission('lesson-edit') === true ? '<a class="dropdown-item" href="' . route('lesson-edit', [$row->class_id, $row->section_id, $row->subject_id]) . '">' . app('translator')->get('common.edit') . '</a>' : ''). 
                    (userPermission('lesson-delete') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                    '<a onclick="deleteLesson(' . $row->id . ');"  class="dropdown-item" href="#" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .
                    '</div>
                </div>';
                }
                return $btn;
            })
            ->rawColumns(['action', 'lesson_name'])
            ->make(true);
        }
    }
}
