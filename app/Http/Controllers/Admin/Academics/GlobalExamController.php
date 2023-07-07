<?php


namespace App\Http\Controllers\Admin\Academics;

use App\User;
use App\SmExam;
use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmSubject;
use App\YearCheck;
use App\SmExamType;
use App\SmClassRoom;
use App\SmExamSetup;
use App\SmMarkStore;
use App\ApiBaseMethod;
use App\SmResultStore;
use App\SmClassSection;
use App\SmClassTeacher;
use App\SmExamSchedule;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Scopes\AcademicSchoolScope;
use App\Scopes\GlobalAcademicScope;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;
use Modules\University\Entities\UnAssignSubject;
use App\Http\Requests\Admin\Examination\SmExamSetupRequest;
use Modules\University\Entities\UnSemesterLabelAssignSection;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;


class GlobalExamController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

   
    
    public function index()
    {
        try {
                $exams = SmExam::withoutGlobalScope(AcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->whereNULL('parent_id')->where('school_id', Auth::user()->school_id)->get();
                $sections = SmSection::withoutGlobalScope(GlobalAcademicScope::class)->where('school_id',auth()->user()->school_id)->whereNULL('parent_id')->get();
                $classes = SmClass::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope( StatusAcademicSchoolScope::class)->where('school_id', Auth::user()->school_id)->with('groupclassSections')->whereNULL('parent_id')->get();
               
                $exams_types = SmExamType::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->where('school_id', Auth::user()->school_id)->whereNULL('parent_id')->get();
            
                $subjects =  SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->orderBy('id', 'DESC')->whereNULL('parent_id')->get();
                $sections = SmSection::get();
                $teachers = SmStaff::where('role_id', 4)->where('active_status', 1)
                ->where('school_id', Auth::user()->school_id)
                ->get(['id', 'user_id', 'full_name']);
                $rooms = SmClassRoom::where('active_status', 1)
                ->where('school_id',Auth::user()->school_id)
                ->get();
            return view('backEnd.global.global_exam', compact('exams', 'classes', 'subjects', 'exams_types', 'sections','teachers','rooms'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function exam_setup($id)
    {
        try {
            $exams = SmExam::get();

            $exams_types = SmExamType::get();

             if (teacherAccess()) {
                $teacher_info=SmStaff::where('user_id', Auth::user()->id)->first();
                $classes= $teacher_info->classes;
            } else {
                $classes = SmClass::get();
            }
            $subjects = SmSubject::get();
            $sections = SmSection::get();
            $selected_exam_type_id = $id;
                
            $teachers = SmStaff::where('role_id', 4)->where('active_status', 1)
            ->where('school_id', Auth::user()->school_id)
            ->get(['id', 'user_id', 'full_name']);
            $rooms = SmClassRoom::where('active_status', 1)
            ->where('school_id',Auth::user()->school_id)
            ->get();
            return view('backEnd.examination.exam', compact('exams', 'classes', 'subjects', 'exams_types', 'sections', 'selected_exam_type_id','teachers','rooms'));
        } catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function exam_reset()
    {
        try {
            $exams = SmExam::get();
            SmExam::query()->truncate();
            $exams_types = SmExamType::get();
            SmExamType::query()->truncate();
            $exam_mark_stores = SmMarkStore::get();
            SmMarkStore::query()->truncate();
            $exam_results_stores = SmResultStore::where('academic_id', getAcademicId())
                                ->where('school_id', Auth::user()->school_id)
                                ->get();
            SmResultStore::query()->truncate();
            SmExamSetup::query()->truncate();
            if (teacherAccess()) {
                $teacher_info=SmStaff::where('user_id', Auth::user()->id)->first();
                $classes= $teacher_info->classes;
            } else {
                $classes = SmClass::get();
            }
            $subjects = SmSubject::get();

            $sections = SmSection::get();
            return view('backEnd.examination.exam', compact('exams', 'classes', 'subjects', 'exams_types', 'sections'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

 //SmExamSetupRequest
    public function store(Request $request)
    { 
        $input = $request->all();
        if($request->exam_system == "single"){
            $validator = Validator::make($input, [
                'exams_type' => 'required',
                'class_id' => 'required',
                'section_ids' => 'required',
                'subject_id' => 'required',
            ]);

        }else{
            $validator = Validator::make($input, [
                'exams_types' => 'required',
                'exam_marks' => 'required|numeric|min:1',
                'subjects_ids' => 'required',
            ]);

        }
    
        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }
       
        try{
            if($request->exam_system == "single"){

                $sections = $request->section_ids;
                foreach($sections as $section){
                    $checkExitExam = SmExam::withoutGlobalScope(AcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->where([
                        'exam_type_id' => $request->exams_type,
                        'class_id' => $request->class_id,
                        'section_id' => $section,
                        'subject_id' => $request->subject_id                       
                    ])->first();
                    
                    if($checkExitExam) {
                        continue;
                    }
                    $exam = new SmExam();
                    $exam->parent_id = null;
                    $exam->exam_type_id = $request->exams_type;
                    $exam->class_id = $request->class_id;
                    $exam->section_id = $section;
                    $exam->subject_id = $request->subject_id;
                    $exam->exam_mark = $request->exam_marks;
                    $exam->pass_mark = $request->pass_mark;
                    $exam->created_by=auth()->user()->id;
                    $exam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                    $exam->school_id = Auth::user()->school_id;
                    $exam->academic_id = getAcademicId();
                    $exam->save();
                    $exam->toArray();
                    $length = count($request->exam_title);
                    for ($i = 0; $i < $length; $i++) {
                        $ex_title = $request->exam_title[$i];
                        $ex_mark = $request->exam_mark[$i];
                        $newSetupExam = new SmExamSetup();
                        $newSetupExam->exam_id = $exam->id;
                        $newSetupExam->class_id =$request->class_id;
                        $newSetupExam->section_id = $section;
                        $newSetupExam->subject_id = $request->subject_id;
                        $newSetupExam->exam_term_id = $request->exams_type;
                        $newSetupExam->exam_title = $ex_title;
                        $newSetupExam->exam_mark = $ex_mark;
                        $newSetupExam->created_by=auth()->user()->id;
                        $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                        $newSetupExam->school_id = Auth::user()->school_id;
                        $newSetupExam->academic_id = getAcademicId();
                        $result = $newSetupExam->save();
                    }
                }
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            }
            else {
                $sections = SmClassSection::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('class_id', $request->class_ids)->whereNULL('parent_id')->get();
                    foreach ($request->exams_types as $exam_type_id) {
                        foreach ($sections as $section) {
                            $subject_for_sections = SmAssignSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->where('class_id', $request->class_ids)
                                                    ->where('section_id', $section->section_id)
                                                    ->get();
    
                            $eligible_subjects = [];
                            foreach ($subject_for_sections as $subject_for_section) {
                                $eligible_subjects[] = $subject_for_section->subject_id;
                            }
    
                            foreach ($request->subjects_ids as $subject_id) {
                                if (in_array($subject_id, $eligible_subjects)) {
                                    $checkExitExam = SmExam::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(AcademicSchoolScope::class)->where([
                                        'exam_type_id' => $request->exams_type,
                                        'class_id' => $request->class_ids,
                                        'section_id' => $section->section_id,
                                        'subject_id' => $request->subject_id                       
                                    ])->first();
                                    
                                    if($checkExitExam) {
                                        continue;
                                    }
                                    $exam = new SmExam();
                                    $exam->parent_id = null;
                                    $exam->exam_type_id = $exam_type_id;
                                    $exam->class_id = $request->class_ids;
                                    $exam->section_id = $section->section_id;
                                    $exam->subject_id = $subject_id;
                                    $exam->exam_mark = $request->exam_marks;
                                    $exam->created_by=auth()->user()->id;
                                    $exam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                    $exam->school_id = Auth::user()->school_id;
                                    $exam->academic_id = getAcademicId();
                                    $exam->save();
                                    $exam->toArray();
                                
                                    $length = count($request->exam_title);
                                    for ($i = 0; $i < $length; $i++) {
                                        $ex_title = $request->exam_title[$i];
                                        $ex_mark = $request->exam_mark[$i];
                                        $newSetupExam = new SmExamSetup();
                                        $newSetupExam->exam_id = $exam->id;
                                        $newSetupExam->class_id = $request->class_ids;
                                        $newSetupExam->section_id = $section->section_id;
                                        $newSetupExam->subject_id = $subject_id;
                                        $newSetupExam->exam_term_id = $exam_type_id;
                                        $newSetupExam->exam_title = $ex_title;
                                        $newSetupExam->exam_mark = $ex_mark;
                                        $newSetupExam->created_by=auth()->user()->id;
                                        $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                        $newSetupExam->school_id = Auth::user()->school_id;
                                        $newSetupExam->academic_id = getAcademicId();
                                        $result = $newSetupExam->save();
                                    }
                                }
                            }
                        }
                    }
                        // DB::commit();
                
                
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            }
        }
        catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        try {
            $exam = SmExam::withoutGlobalScope(AcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->find($id);
            $exams = SmExam::withoutGlobalScope(AcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->whereNULL('parent_id')->where('school_id', Auth::user()->school_id)->get();
            $sections = SmSection::withoutGlobalScope(GlobalAcademicScope::class)->where('school_id',auth()->user()->school_id)->whereNULL('parent_id')->get();
            $classes = SmClass::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope( StatusAcademicSchoolScope::class)->where('school_id', Auth::user()->school_id)->with('groupclassSections')->whereNULL('parent_id')->get();
            $exams_types = SmExamType::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->where('school_id', Auth::user()->school_id)->whereNULL('parent_id')->get();
            $subjects =  SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->orderBy('id', 'DESC')->whereNULL('parent_id')->get();
            return view('backEnd.global.global_examEdit', compact('exam', 'exams', 'classes', 'subjects', 'sections', 'exams_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
      
        DB::beginTransaction();
        try {
            // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $exam = SmExam::withoutGlobalScope(AcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->find($id);
            $exam->exam_mark = $request->exam_marks;
            $exam->pass_mark = $request->pass_mark;
            $exam->updated_by=auth()->user()->id;
            $exam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
            $exam->save();
            SmExamSetup::where('exam_id', $id)->delete();
            $length = count($request->exam_title);
            for ($i = 0; $i < $length; $i++) {
                $ex_title = $request->exam_title[$i];
                $ex_mark = $request->exam_mark[$i];
                $newSetupExam = new SmExamSetup();
                $newSetupExam->exam_term_id =$exam->exam_type_id;
                $newSetupExam->class_id = $exam->class_id;
                $newSetupExam->section_id = $exam->section_id;
                $newSetupExam->subject_id = $exam->subject_id;
                $newSetupExam->exam_id = $exam->id;
                $newSetupExam->exam_title = $ex_title;
                $newSetupExam->exam_mark = $ex_mark;
                $newSetupExam->updated_by=auth()->user()->id;
                $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                $newSetupExam->school_id = Auth::user()->school_id;
                $newSetupExam->academic_id = getAcademicId();
                $newSetupExam->save();
            } //end loop exam setup loop
            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect('global-exam');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function examSetup($id)
    {
        try {
            $exam = SmExam::find($id);
            $exams = SmExam::get();
                if (teacherAccess()) {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $classes= $teacher_info->classes;
            } else {
                $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();
            } 
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $sections = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.exam_setup', compact('exam', 'exams', 'classes', 'subjects', 'sections'));
        } catch (\Exception $e) {
          
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function examSetupStore(Request $request)
    {
        try {
            $class_id = $request->class;
            $section_id = $request->section;
            $subject_id = $request->subject;
            $exam_term_id = $request->exam_term_id;

            $total_exam_mark = $request->total_exam_mark;
            $totalMark = $request->totalMark;

            if ($total_exam_mark == $totalMark) {
                $length = count($request->exam_title);
                for ($i = 0; $i < $length; $i++) {
                    $ex_title = $request->exam_title[$i];
                    $ex_mark = $request->exam_mark[$i];

                    $newSetupExam = new SmExamSetup();
                    $newSetupExam->class_id = $class_id;
                    $newSetupExam->section_id = $section_id;
                    $newSetupExam->subject_id = $subject_id;
                    $newSetupExam->exam_term_id = $exam_term_id;
                    $newSetupExam->exam_title = $ex_title;
                    $newSetupExam->exam_mark = $ex_mark;
                    $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                    $newSetupExam->school_id = Auth::user()->school_id;
                    $newSetupExam->academic_id = getAcademicId();
                    $result = $newSetupExam->save();
                    if ($result) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect('exam');
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                }
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            try {
               // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                SmExamSetup::where('exam_id', $id)->delete();
                $exam = SmExam::find($id);
                $is_exist= SmExamSchedule::where('exam_id',$exam->id)->where('school_id', Auth::user()->school_id)->first();
                if($is_exist){
                    $is_exist->delete();
                }
                $exam->delete();
                DB::commit();
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } catch (\Illuminate\Database\QueryException $e) {
                Toastr::error('This item already used', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getClassSubjects(Request $request)
    {
        try {
            $subjects = SmAssignSubject::where('class_id', $request->id)
            ->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)
            ->get();

            $subjects = $subjects->groupBy('subject_id');

            $assinged_subjects = [];
            foreach ($subjects as $key => $subject) {
                $assinged_subjects[] = SmSubject::find($key);
            }
            return response()->json($assinged_subjects);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }


    public function subjectAssignCheck(Request $request)
    {
        try {
            $exam = [];
            $assigned_subjects = [];
            foreach ($request->exam_types as $exam_type) {
                $exam = SmExam::where('exam_type_id', $exam_type)->where('class_id', $request->class_id)->where('subject_id', $request->id)->first();

                if ($exam != "") {
                    $exam_title = SmExamType::find($exam_type);

                    $assigned_subjects[] = $exam_title->title;
                }
            }
            return response()->json($assigned_subjects);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function examView(Request $request){   
             
        $input = $request->only(['code']);
        $exams_types = SmExamType::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->where('school_id', Auth::user()->school_id)->whereNULL('parent_id')->get();
        $classes = SmClass::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope( StatusAcademicSchoolScope::class)->where('school_id', Auth::user()->school_id)->with('groupclassSections')->whereNULL('parent_id')->get();
        $teachers = SmStaff::where('role_id', 4)->where('active_status', 1)
                ->where('school_id', Auth::user()->school_id)
                ->get(['id', 'user_id', 'full_name']);
    
        if($input['code'] == "single"){
            $view = "backEnd.examination.exam_setup.single_exam_setup";
        }elseif($input['code'] == "multi"){
            $view = "backEnd.examination.exam_setup.multi_exam_setup";
        }
        $rooms = SmClassRoom::where('active_status', 1)
                ->where('school_id',Auth::user()->school_id)
                ->get();
        $html = view($view,compact('exams_types','classes','teachers','rooms'))->render();

        return response()->json([
            'status' => true,
            'html' => $html,

        ]);
    }

    public function customMarksheetReport()
    { 
        try{
            $exams = SmExamType::get();
            $classes = SmClass::get();
            return view('backEnd.examination.report.marksheetReport', compact('exams','classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

}