<?php

namespace App\Http\Controllers\Admin\Academics;

use App\SmExam;
use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmSubject;
use App\YearCheck;
use App\SmExamType;
use App\SmExamSetup;
use App\ApiBaseMethod;
use App\SmClassSection;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use App\Traits\CcAveuneTrait;
use App\SmTeacherUploadContent;
use App\Scopes\AcademicSchoolScope;
use App\Scopes\GlobalAcademicScope;
use App\Events\CreateClassGroupChat;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;

class GlobalAssignSubjectController extends Controller
{
    use CcAveuneTrait;
    public function __construct()
    {
        $this->middleware('PM');
     
    }
    public function index(Request $request)
    {
        try {
            $classes = SmClass::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->with('groupclassSections')->where('school_id', Auth::user()->school_id)->whereNULL('parent_id')->get();
            return view('backEnd.global.global_assign_subject', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function create(Request $request)
    {
        try {
            $classes = SmClass::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->with('groupclassSections')->where('school_id', Auth::user()->school_id)->whereNULL('parent_id')->get();
            return view('backEnd.global.global_assign_subject_create', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function ajaxSubjectDropdown(Request $request)
    {
        try {
            $staff_info = SmStaff::where('user_id', Auth::user()->id)->first();
            if (teacherAccess()) {
                $class_id = $request->class;
                $allSubjects = SmAssignSubject::where([['section_id', '=', $request->id], ['class_id', $class_id], ['teacher_id', $staff_info->id]])->where('school_id', Auth::user()->school_id)->get();
                $subjectsName = [];
                foreach ($allSubjects as $allSubject) {
                    $subjectsName[] = SmSubject::find($allSubject->subject_id);
                }
            } else {
                $class_id = $request->class;
                $allSubjects = SmAssignSubject::where([['section_id', '=', $request->id], ['class_id', $class_id]])->where('school_id', Auth::user()->school_id)->get();

                $subjectsName = [];
                foreach ($allSubjects as $allSubject) {
                    $subjectsName[] = SmSubject::find($allSubject->subject_id);
                }
            }
            return response()->json([$subjectsName]);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Error msg'], 404);
        }
    }

    public function search(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
             'section' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {

            $assign_subjects=SmAssignSubject::query();
            $assign_subjects= $assign_subjects->where('class_id',$request->class);

            if($request->section !=null){
                $assign_subjects= $assign_subjects->where('section_id',$request->section);
            }

            $assign_subjects=$assign_subjects->where('school_id',Auth::user()->school_id)->get();
            $subjects = SmSubject::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $teachers = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->get();
            $class_id = $request->class;
            $section_id = $request->section;
            $classes = SmClass::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->with('groupclassSections')->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.global.global_assign_subject_create', compact('classes', 'assign_subjects', 'teachers', 'subjects', 'class_id', 'section_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function assignSubjectAjax(Request $request)
    {
        try {
            $subjects = SmSubject::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $teachers = SmStaff::status()->where('role_id', 4)->get();
            return response()->json([$subjects, $teachers]);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Error msg'], 404);
        }
    }

    public function assignSubjectStore(Request $request)
    {
        try {
            if(empty($request->all())) {
                Toastr::error('Operation failed', 'Error');
                return redirect()->back();
            }
            if ($request->update == 0) {
                $i = 0;
                //  $k = 0;
                if (isset($request->subjects)) {
                    foreach ($request->subjects as $key=>$subject) {
                        if ($subject != "") {                            
                            if($request->section_id==null){
                                $k = 0;
                                $all_section=SmClassSection::where('class_id',$request->class_id)->get();
                               $t_teacher=count($request->teachers);
                                foreach($all_section as $section){                                        
                                    $assign_subject = new SmAssignSubject();
                                    $assign_subject->class_id = $request->class_id;
                                    $assign_subject->school_id = Auth::user()->school_id;
                                    $assign_subject->section_id = $section->section_id;
                                    $assign_subject->subject_id = $subject;                            
                                    $assign_subject->teacher_id = $request->teachers[$key];                                
                                    $assign_subject->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                    $assign_subject->academic_id = getAcademicId();
                                    $assign_subject->save();
                                    //event(new CreateClassGroupChat($assign_subject));
                                    $k++;
                                }

                            }else{
                            $assign_subject = new SmAssignSubject();
                            $assign_subject->class_id = $request->class_id;
                            $assign_subject->school_id = Auth::user()->school_id;
                            $assign_subject->section_id = $request->section_id;
                            $assign_subject->subject_id = $subject;
                            $assign_subject->teacher_id = $request->teachers[$i];
                            $assign_subject->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            $assign_subject->academic_id = getAcademicId();
                            $assign_subject->save();
                           // event(new CreateClassGroupChat($assign_subject));
                            $i++;
                            }
                        }
                    }
                }
            } elseif ($request->update == 1) {
                if($request->section_id ==null){
                    $assign_subjects = SmAssignSubject::where('class_id', $request->class_id)->delete();

                    $i = 0;
                    if (! empty($request->subjects)) {
            
                        foreach ($request->subjects as $key=>$subject) {
                            $k = 0;
                            if (!empty($subject)) {

                                $all_section=SmClassSection::where('class_id',$request->class_id)->get();
                                foreach($all_section as $section){
                         
                                $assign_subject = new SmAssignSubject();
                                $assign_subject->class_id = $request->class_id;
                                $assign_subject->section_id = $section->section_id;
                                $assign_subject->subject_id = $subject;
                                $assign_subject->teacher_id = $request->teachers[$key];
                                $assign_subject->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                $assign_subject->academic_id = getAcademicId();
                                $assign_subject->school_id = Auth::user()->school_id;

                                
                                $assign_subject->save();
                                //event(new CreateClassGroupChat($assign_subject));
                                $k++;
                                }
                            }
                        }
                    }

                }else{
                    SmAssignSubject::where('class_id', $request->class_id)->where('section_id', $request->section_id)->delete();
               
                    $i = 0;
                    if (! empty($request->subjects)) {
            
                        foreach ($request->subjects as $subject) {
                                
                            if (!empty($subject)) {
                                $assign_subject = new SmAssignSubject();
                                $assign_subject->class_id = $request->class_id;
                                $assign_subject->section_id = $request->section_id;
                                $assign_subject->subject_id = $subject;
                                $assign_subject->teacher_id = $request->teachers[$i];
                                $assign_subject->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                $assign_subject->academic_id = getAcademicId();
                                $assign_subject->school_id = Auth::user()->school_id;
                                $result =  $assign_subject->save();
                                // event(new CreateClassGroupChat($assign_subject));
                                $i++;
                            }
                        }
                    }
             }
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function assignSubjectFind(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required'
        ]);
        try {
            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->get();
            $subjects = SmSubject::get();
            $teachers = SmStaff::status()->where('role_id', 4)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if ($assign_subjects->count() == 0) {
                Toastr::error('No Result Found', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'No Result Found');
            } else {
                $class_id = $request->class;
                return view('backEnd.global.global_assign_subject', compact('classes', 'assign_subjects', 'teachers', 'subjects', 'class_id'));
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function ajaxSelectSubject(Request $request)
    {
        try {
            $subject_all = SmAssignSubject::where('class_id', '=', $request->class)->where('section_id', $request->section)->distinct('subject_id')->where('school_id', Auth::user()->school_id)->get();
            $students = [];
            foreach ($subject_all as $allSubject) {
                $students[] = SmSubject::find($allSubject->subject_id);
            }
            return response()->json([$students]);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Error msg'], 404);
        }
    }

    public function loadAssignedSubject(Request $request){
        
        $assignedClass = SmClassSection::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->find($request->assignedClass);
        if($assignedClass){
            $teachers = SmStaff::where('role_id',4)->where('school_id',Auth::user()->school_id)->get();
            $class_id = $assignedClass->class_id;
            $section_id = $assignedClass->section_id;
            $subjects = SmAssignSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->where('class_id',$class_id)->where('section_id',$section_id)->with('subject')->get();
            $globalStudyMat = SmTeacherUploadContent::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('class',$class_id)->where('section',$section_id)->count();
           
            $data['subjects'] = $subjects;
            $data['class_id'] = $class_id;
            $data['teachers'] = $teachers;
            $html = view("backEnd.global.ajax_assigned_subject_list",compact('subjects','section_id','class_id','teachers','globalStudyMat'))->render();
            $html2 = view("backEnd.global.ajax_assigned_study_mat_list",compact('assignedClass','globalStudyMat'))->render();
            return response()->json([
                'class_id' => $class_id,
                'html' => $html,
                'html2' =>$html2

            ]);
       
            // return view('backEnd.global.ajax_assigned_subject_list',compact('class_id','subjects'));
            View::make("backEnd.global.ajax_assigned_subject_list")->with('data')->render();
            $html= "";
            foreach($subjects as $subject){
                $html.= 
                    '<div class="row mb-20">' . 
                        '<div class="col-lg-5 mt-30-md">' . 
                            '<select class="primary_select form-control" name="subjects[]" id="subjects">' .
                                '<option data-display="'.@$subject->subject->subject_name.'" value="'.@$subject->subject->id.'">'.@$subject->subject->subject_name.'</option>' .
                            '</select>' . 
                        '</div>' . 
                        '<div class="col-lg-5 mt-30-md">' . 
                        '<select class="primary_select form-control" name="teachers[]" id="teachers">' ; 

                        foreach($teachers  as $teacher){
                            $html.=  '<option  value="'.@$teacher->id.'">'.@$teacher->full_name.'</option>' ;
                        }

                         $html.=  '</select>' . 
                        '</div>' .
                        '<div class="col-lg-2"> ' . 
                            '<button class="primary-btn icon-only fix-gr-bg" type="button">' .
                                '<span class="ti-trash" id="removeSubject" onclick="deleteSubject('.$subject->id.')"></span>' . 
                            '</button>' .
                        '</div>'. 
                    '</div>' ;
            }
           
            
            return response()->json(['htmlCode' => $html,'class_id' =>$class_id]);
           // return $data;
        }
    }

    public function saveAssignedSubject(Request $request){
      try{
            $global_assignedClass = SmClassSection::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->find($request->section);
            
            if($global_assignedClass){
                $global_class = SmClass::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->find($global_assignedClass->class_id);
                $global_section = SmSection::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->find($global_assignedClass->section_id);
                $existClass = SmClass::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->where('parent_id',$global_class->id)->first();
                $existSection = SmSection::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->where('parent_id',$global_section->id)->first();
                
                if(! $existClass){
                    $class = new SmClass();
                    $class->parent_id = $global_class->id;
                    $class->class_name = $global_class->class_name;
                    $class->pass_mark = $global_class->pass_mark;
                    $class->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                    $class->created_by=auth()->user()->id;
                    $class->school_id = Auth::user()->school_id;
                    $class->academic_id = getAcademicId();
                    $class->save();
                }else{
                    $class = $existClass;
                }
                
                if(! $existSection){
                    $section = new SmSection();
                    $section->parent_id = $global_section->id;
                    $section->section_name = $global_section->section_name;
                    $section->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                    $section->school_id = Auth::user()->school_id;
                    $section->created_at=auth()->user()->id;
                    $section->academic_id = !moduleStatusCheck('University') ? getAcademicId() : null;
                    $section->save();
                }else{
                    $section = $existSection;
                }
                $existClassSection = SmClassSection::where('class_id',$class->id)->where('section_id',$section->id)->first();
                if(! $existClassSection){
                    $smClassSection = new SmClassSection();
                    $smClassSection->parent_id = $global_assignedClass->id;
                    $smClassSection->class_id = $class->id;
                    $smClassSection->section_id = $section->id;
                    $smClassSection->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                    $smClassSection->school_id = Auth::user()->school_id;
                    $smClassSection->academic_id = getAcademicId();
                    $smClassSection->save();
                }

            }
            if (isset($request->subjects)) {
                foreach ($request->subjects as $key=>$subject) {
                    if ($subject != "") { 
                        $global_sub = SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->find($subject);
                        $existSubject = SmSubject::where('parent_id',$global_sub->id)->first();
                        if(! $existSubject){
                            $new_subject = new SmSubject();
                            $new_subject->parent_id = $global_sub->id;
                            $new_subject->subject_name = $global_sub->subject_name;
                            $new_subject->subject_type = $global_sub->subject_type;
                            $new_subject->subject_code = $global_sub->subject_code;
                            if (@generalSetting()->result_type == 'mark'){
                                $new_subject->pass_mark = $global_sub->pass_mark;
                            }
                            $new_subject->created_by   = auth()->user()->id;
                            $new_subject->school_id    = auth()->user()->school_id;
                            $new_subject->academic_id  = getAcademicId();
                            $new_subject->save();
                        }else{
                            $new_subject= $existSubject;
                        }
                        
                        SmAssignSubject::where('class_id',$class->id)->where('section_id',$section->id)->where('subject_id',$new_subject->id)->delete();
                        $assign_subject = new SmAssignSubject();
                        $assign_subject->parent_id = $global_assignedClass->id;
                        $assign_subject->class_id = $class->id;
                        $assign_subject->section_id = $section->id;
                        $assign_subject->school_id = Auth::user()->school_id;
                        $assign_subject->subject_id = $new_subject->id;
                        $assign_subject->teacher_id = $request->teachers[$key];
                        $assign_subject->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                        $assign_subject->academic_id = getAcademicId();
                        $assign_subject->save();
                        @event(new CreateClassGroupChat($assign_subject));
                      
                    }
                }

                if($request->exams){
                    foreach($request->exams as $exam_id){
                        $parentExam = SmExam::withoutGlobalScope(AcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->find($exam_id);
                        $subject = SmSubject::where('parent_id', $parentExam->subject_id)->first(); 
                        $class = SmClass::where('parent_id', $parentExam->class_id)->first();
                        $section = SmSection::where('parent_id', $parentExam->section_id)->first();
                        $examType = SmExamType::where('parent_id', $parentExam->exam_type_id)->first();
                       
                        if($parentExam){
                            $parentExamSetups = SmExamSetup::where('exam_id',$parentExam->id)->get();
                            if(! $examType){
                                $parentExamType = SmExamType::withoutGlobalScope(AcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->where('id', $parentExam->exam_type_id)->first();
                                $newExamType = new SmExamType();
                                $newExamType->parent_id = $parentExamType->id;
                                $newExamType->title = $parentExamType->title;
                                $newExamType->active_status = 1;
                                $newExamType->school_id = Auth::user()->school_id;
                                $newExamType->updated_by = Auth::user()->id;
                                $newExamType->save();
                                $exam_type_id = $newExamType->id;
                            }else{
                                $exam_type_id = $examType->id;
                            }
        
                            $newExam = new SmExam();
                            $newExam->parent_id = $parentExam->id;
                            $newExam->class_id = $class->id;
                            $newExam->section_id = $section->id;
                            $newExam->subject_id = $new_subject->id;
                            $newExam->exam_type_id = $exam_type_id;
                            $newExam->exam_mark = $parentExam->exam_mark;
                            $newExam->pass_mark = $parentExam->pass_mark;
                            $newExam->created_by=auth()->user()->id;
                            $newExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            $newExam->school_id = Auth::user()->school_id;
                            $newExam->academic_id = getAcademicId();
                            $result = $newExam->save();
                            if($result){
                                foreach($parentExamSetups as $parentExamSetup){
                                    $newSetupExam = new SmExamSetup();
                                    $newSetupExam->exam_id = $newExam->id;
                                    $newSetupExam->class_id =$newExam->class_id;
                                    $newSetupExam->section_id = $newExam->section_id;
                                    $newSetupExam->subject_id = $newExam->subject_id;
                                    $newSetupExam->exam_term_id = $exam_type_id;
                                    $newSetupExam->exam_title = $parentExamSetup->exam_title;
                                    $newSetupExam->exam_mark = $parentExamSetup->exam_mark;
                                    $newSetupExam->created_by= auth()->user()->id;
                                    $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                                    $newSetupExam->school_id = Auth::user()->school_id;
                                    $newSetupExam->academic_id = getAcademicId();
                                    $result = $newSetupExam->save();
                                }
        
                            }
                        }
                    }
        
                }
            }
            $status = true;
            $message = __('student.Operation Sucessfull');
            return response()->json(['status'=>$status, 'message'=>$message]);
        } catch (\Throwable $th) {
            $status = false;
            $message = __('student.Operation Failed');
            return response()->json(['status'=>$status, 'message'=>$th->getMessage()]);
        }
    }


    public function globalAssign(){
        $classes = SmClass::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope( StatusAcademicSchoolScope::class)->where('school_id', Auth::user()->school_id)->with('groupclassSections')->whereNULL('parent_id')->get();
        return view('backEnd.global.globalAssign',compact('classes'));

    }

    public function ccAve(){

        return view('backEnd.global.ccAve');
    }

    function ccAvePost(Request $request){
        error_reporting(0);
        $merchant_data='';
        $working_key='F7AFD5A9D46D23CEFE51F470F762E62B';//Shared by CCAVENUES
        $access_code='AVDX66KE05BI57XDIB';//Shared by CCAVENUES
        foreach ($request->except('_token') as $key => $value){
            $merchant_data.=$key.'='.urlencode($value).'&';
        }
        $encrypted_data= $this->encrypt($merchant_data,$working_key);
        return view('backEnd.global.ccAve2',compact('access_code','encrypted_data'));
       
    }

    function ccAveRes(Request $request){
    error_reporting(0);
	$workingKey='25DBE58B3E2633BB1FB1CB0B74C42A5F';		//Working Key should be provided here.
	$encResponse=$request->encResp;			//This is the response sent by the CCAvenue Server
	$rcvdString= $this->decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status="";
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);
	echo "<center>";

	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==3)	$order_status=$information[1];
	}

	if($order_status==="Success")
	{
		echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
		
	}
	else if($order_status==="Aborted")
	{
		echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
	
	}
	else if($order_status==="Failure")
	{
		echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
	}
	else
	{
		echo "<br>Security Error. Illegal access detected";
	
	}

	echo "<br><br>";

	echo "<table cellspacing=4 cellpadding=4>";
	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
	    	echo '<tr><td>'.$information[0].'</td><td>'.urldecode($information[1]).'</td></tr>';
	}

	echo "</table><br>";
	echo "</center>";
    }
}
