<?php

namespace Modules\Lesson\Http\Controllers;
use DataTables;
use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmSubject;
use App\YearCheck;
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
use Modules\Lesson\Entities\SmLessonTopicDetail;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;

class SmTopicController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function index()
    {
        try {
            $data = $this->loadTopic();
            if (moduleStatusCheck('University')) {
                return view('university::topic.topic', $data);
            } else {
                return view('lesson::topic.topic', $data);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
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
                    'lesson' => 'required',
                ],
            );
        } else {
            $request->validate(
                [
                    'class' => 'required',
                    'subject' => 'required',
                    'section' => 'required',
                    'lesson' => 'required',
                ],
            );
        }
        DB::beginTransaction();
        if (moduleStatusCheck('University')) {
            $is_duplicate = SmLessonTopic::where('school_id', Auth::user()->school_id)
                                        ->where('un_session_id', $request->un_session_id)
                                        ->when($request->un_faculty_id, function ($query) use ($request) {
                                            $query->where('un_faculty_id', $request->un_faculty_id);
                                        })->where('un_department_id', $request->un_department_id)
                                        ->where('un_academic_id', $request->un_academic_id)
                                        ->where('un_semester_id', $request->un_department_id)
                                        ->where('un_semester_label_id', $request->un_academic_id)
                                        ->where('un_subject_id', $request->un_subject_id)
                                        ->where('lesson_id', $request->lesson)
                                        ->first();
        } else {
            $is_duplicate = SmLessonTopic::where('school_id', Auth::user()->school_id)
                                        ->where('class_id', $request->class)
                                        ->where('lesson_id', $request->lesson)
                                        ->where('section_id', $request->section)
                                        ->where('subject_id', $request->subject)
                                        ->where('academic_id', getAcademicId())
                                        ->first();
        }

        if ($is_duplicate) {
            $length = count($request->topic);
            for ($i = 0; $i < $length; $i++) {
                $topicDetail = new SmLessonTopicDetail;
                $topic_title = $request->topic[$i];
                $topicDetail->topic_id = $is_duplicate->id;
                $topicDetail->topic_title = $topic_title;
                $topicDetail->lesson_id = $request->lesson;
                $topicDetail->school_id = Auth::user()->school_id;
                if(moduleStatusCheck('University')){
                    $topicDetail->un_academic_id = getAcademicId();
                }else{
                    $topicDetail->academic_id = getAcademicId();
                }
                $topicDetail->save();
            }
            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } else {
            try {
                $smTopic = new SmLessonTopic;
                $smTopic->class_id = $request->class;
                $smTopic->section_id = $request->section;
                $smTopic->subject_id = $request->subject;
                $smTopic->lesson_id = $request->lesson;
                $smTopic->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                $smTopic->school_id = Auth::user()->school_id;
                if (moduleStatusCheck('University')) {
                    $common = App::make(UnCommonRepositoryInterface::class);
                    $common->storeUniversityData($smTopic, $request);
                }else{
                    $smTopic->academic_id = getAcademicId();
                }
                $smTopic->save();
                $smTopic_id = $smTopic->id;
                $length = count($request->topic);
                for ($i = 0; $i < $length; $i++) {
                    $topicDetail = new SmLessonTopicDetail;
                    $topic_title = $request->topic[$i];
                    $topicDetail->topic_id = $smTopic_id;
                    $topicDetail->topic_title = $topic_title;
                    $topicDetail->lesson_id = $request->lesson;
                    $topicDetail->school_id = Auth::user()->school_id;
                    if(!moduleStatusCheck('University')){
                        $topicDetail->academic_id = getAcademicId();
                    }
                    $topicDetail->save();
                }
                DB::commit();

                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
    }

    public function edit($id)
    {

        try {
            $data = $this->loadTopic();
            $data['topic'] = SmLessonTopic::where('academic_id', getAcademicId())
            ->where('id', $id)->where('school_id', Auth::user()->school_id)->first();
            $data['lessons'] = SmLesson::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $data['topicDetails'] = SmLessonTopicDetail::where('topic_id', $data['topic']->id)->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)->get();
            if (moduleStatusCheck('University')) {

                $request = [
                    'semester_id' => $data['topic']->un_semester_id,
                    'academic_id' => $data['topic']->un_academic_id,
                    'session_id' => $data['topic']->un_session_id,
                    'department_id' => $data['topic']->un_department_id,
                    'faculty_id' => $data['topic']->un_faculty_id,
                    'semester_label_id' => $data['topic']->un_semester_label_id,
                    'subject_id' => $data['topic']->un_subject_id,
                ];
                $interface = App::make(UnCommonRepositoryInterface::class);
              
                $data += $interface->getCommonData($data['topic']);
                return view('university::topic.edit_topic', $data);
            }
            return view('lesson::topic.editTopic', $data);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }
    public function updateTopic(Request $request)
    {
   
        try {
            $length = count($request->topic);
            for ($i = 0; $i < $length; $i++) {
                $topicDetail = SmLessonTopicDetail::find($request->topic_detail_id[$i]);
                $topic_title = $request->topic[$i];
                $topicDetail->topic_title = $topic_title;
                $topicDetail->school_id = Auth::user()->school_id;
                $topicDetail->academic_id = getAcademicId();
                $topicDetail->save();
            }

            Toastr::success('Operation successful', 'Success');
            return redirect('/lesson/topic');

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }
    public function topicdelete(Request $request)
    {
        $id = $request->id;
        $topic = SmLessonTopic::find($id);
        $topic->delete();
        $topicDetail = SmLessonTopicDetail::where('topic_id', $id)->get();
        if ($topicDetail) {
            foreach ($topicDetail as $data) {
                SmLessonTopicDetail::destroy($data->id);
                LessonPlanner::where('topic_detail_id', $data->id)->get();
            }
        }

        $topicLessonPlan = LessonPlanner::where('topic_id', $id)->get();
        if ($topicLessonPlan) {
            foreach ($topicLessonPlan as $topic_data) {
                LessonPlanner::destroy($topic_data->id);
            }
        }

        Toastr::success('Operation successful', 'Success');
        return redirect()->route('lesson.topic');

    }
    public function deleteTopicTitle($id)
    {
        SmLessonTopicDetail::destroy($id);
        $topicDetail = LessonPlanner::where('topic_detail_id', $id)->get();
        if ($topicDetail) {
            foreach ($topicDetail as $data) {
                LessonPlanner::destroy($data->id);
            }
        }

        Toastr::success('Operation successful', 'Success');
        return redirect()->back();
    }


    public function getAllTopicsAjax(Request $request){

        if($request->ajax()){
            if (Auth::user()->role_id == 4) {
                $subjects = SmAssignSubject::select('subject_id')->where('teacher_id', $teacher_info->id)->get();
                $topics = SmLessonTopic::with('lesson', 'class', 'section', 'subject')->whereIn('subject_id', $subjects)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
    
            } else {
                $topics = SmLessonTopic::with('lesson', 'class', 'section', 'subject')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            }
            return Datatables::of($topics)
            ->addIndexColumn()
            ->addColumn('topics_name', function ($row){
                $topics_name = "";
                $topics_title = $row->topics;
                foreach($topics_title as $topicData){
                    $topics_name.= $topicData->topic_title;
                        if(($topics_title->last()) != $topicData){
                            $topics_name.= ',';
                        }
                }
                return $topics_name;
            })
            ->addColumn('action', function ($row) {
               
                $btn = '<div class="dropdown CRM_dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                    <div class="dropdown-menu dropdown-menu-right">' .
                    (userPermission('topic-edit') === true ? '<a class="dropdown-item" href="' . route('topic-edit', $row->id) . '">' . app('translator')->get('common.edit') . '</a>' : '').
                    (userPermission('topic-delete') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a onclick="deleteTopic(' . $row->id . ');"  class="dropdown-item" href="#" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .
                    '</div>
                </div>';

                return $btn;
            })
            ->rawColumns(['action', 'topics_name'])
            ->make(true);

        }
        
    }

    
    public function loadTopic()
    {
        $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
        if (Auth::user()->role_id == 4) {
            $subjects = SmAssignSubject::select('subject_id')->where('teacher_id', $teacher_info->id)->get();
            $data['topics'] = SmLessonTopic::with('lesson', 'class', 'section', 'subject')->whereIn('subject_id', $subjects)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

        } else {
            $data['topics'] = SmLessonTopic::with('lesson', 'class', 'section', 'subject')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        }

        if (!teacherAccess()) {
            $data['classes'] = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        } else {
            $data['classes'] = SmAssignSubject::where('teacher_id', $teacher_info->id)
                ->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                ->where('sm_assign_subjects.active_status', 1)
                ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                ->where('sm_assign_subjects.academic_id', getAcademicId())
                ->select('sm_classes.id', 'class_name')
                ->get();
        }
        $data['subjects'] = SmSubject::get();
        $data['sections'] = SmSection::get();
        return $data;
    }
}

