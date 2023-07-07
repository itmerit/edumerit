<?php

namespace App\Http\Controllers\Admin\StudentInfo;

use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmVehicle;
use App\SmRoomList;
use App\SmExamSetup;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmAssignSubject;
use App\SmAssignVehicle;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Scopes\GlobalAcademicScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\StatusAcademicSchoolScope;
use App\Http\Controllers\Admin\StudentInfo\SmStudentReportController;

class SmStudentAjaxController extends Controller
{

    public function __construct()
    {
        $this->middleware('PM');
     
    }
    public function ajaxSectionSibling(Request $request)
    {
        try {
            $sectionIds = SmClassSection::where('class_id', '=', $request->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $sibling_sections = [];
            foreach ($sectionIds as $sectionId) {
                $sibling_sections[] = SmSection::find($sectionId->section_id);
            }
            return response()->json([$sibling_sections]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }
    public function ajaxSiblingInfo(Request $request)
    {
        try {
            $records = StudentRecord::query();
            $records->where('is_promote', 0)->where('school_id',auth()->user()->school_id);
            $records->when($request->filled('class_id'), function ($u_query) use ($request) {
                $u_query->where('class_id', $request->class_id);
            }, function ($query) use ($request) {
                $query->when($request->section_id, function ($query) use ($request) {
                    $query->where('section_id', $request->section_id);
                });
            });
            $student_records = $records->whereHas('student')->get(['student_id'])->unique('student_id')->toArray();
            $siblings =  SmStudent::whereIn('id',$student_records)
                        ->where('id', '!=', $request->id)->where('active_status', 1)->get();
        
            return response()->json($siblings);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }


    public function ajaxSiblingInfoDetail(Request $request)
    {
        try {
            $staff = $request->staff_id ?  SmStaff::with('roles')->find($request->staff_id) : null;
            $sibling_detail = $request->id ? SmStudent::find($request->id) : null;
            $parent_detail =  $sibling_detail ? $sibling_detail->parents : null;
            $type = $staff ?  'staff' : 'sibling';
            return response()->json([$sibling_detail, $parent_detail, $staff, $type]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxGetVehicle(Request $request)
    {
        try {
           
            $school_id = 1;
            if(Auth::check()){
                $school_id = Auth::user()->school_id;
            } else if(app()->bound('school')){
                $school_id = app('school')->id;
            }
            $vehicle_detail = SmAssignVehicle::where('route_id', $request->id)->where('school_id', $school_id)->first();
            $vehicles = explode(',', $vehicle_detail->vehicle_id);
            $vehicle_info = SmVehicle::whereIn('id', $vehicles)->get();
           
            return response()->json([$vehicle_info]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxVehicleInfo(Request $request)
    {
        try {
            $vehivle_detail = SmVehicle::find($request->id);
            return response()->json([$vehivle_detail]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxRoomDetails(Request $request)
    {
        try {
            $school_id = 1;
            if(Auth::check()){
                $school_id = Auth::user()->school_id;
            } else if(app()->bound('school')){
                $school_id = app('school')->id;
            }

            $room_details = SmRoomList::where('dormitory_id', '=', $request->id)->where('school_id', $school_id)->get();
            $rest_rooms = [];
            foreach ($room_details as $room_detail) {
                $count_room = SmStudent::where('room_id', $room_detail->id)->count();
                if ($count_room < $room_detail->number_of_bed) {
                    $rest_rooms[] = $room_detail;
                }
            }
            return response()->json([$rest_rooms]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxGetRollId(Request $request)
    {

        try {
            $max_roll = SmStudent::where('class_id', $request->class)
                        ->where('section_id', $request->section)
                        ->where('school_id', Auth::user()->school_id)
                        ->max('roll_no');
            // return $max_roll;
            if ($max_roll == "") {
                $max_roll = 1;
            } else {
                $max_roll = $max_roll + 1;
            }
            return response()->json([$max_roll]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxGetRollIdCheck(Request $request)
    {
        try {
            $roll_no = SmStudent::where('class_id', $request->class)
                    ->where('section_id', $request->section)
                    ->where('roll_no', $request->roll_no)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
            return response()->json($roll_no);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }
    
    public function ajaxSubjectClass(Request $request)
    {
        try {

            if($request->globalType){
                $subjects = SmAssignSubject::query();
                if ($request->id !="all_class") {
                    $subjects->where('class_id', '=', $request->id);
                } else {
                    $subjects->distinct('class_id');
                }
                $subjectIds = $subjects->withoutGlobalScope(StatusAcademicSchoolScope::class)->distinct('subject_id')->get()->pluck(['subject_id'])->toArray();

                $subjects = SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->whereIn('id', $subjectIds)->get(['id','subject_name']);
            }else{
                $subjects = SmAssignSubject::query();
                if (teacherAccess()) {
                    $subjects->where('teacher_id', Auth::user()->staff->id) ;
                }
                if ($request->id !="all_class") {
                    $subjects->where('class_id', '=', $request->id);
                } else {
                    $subjects->distinct('class_id');
                }
                $subjectIds= $subjects->get()->pluck(['subject_id'])->toArray();
                
     
                $subjects=SmSubject::whereIn('id', $subjectIds)->get(['id','subject_name']);
            }
            
            
            return response()->json([$subjects]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    
    public function ajaxStudentPromoteSection(Request $request)
    {
        if($request->parent){
            $class = SmClass::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope( StatusAcademicSchoolScope::class)->where('school_id', Auth::user()->school_id)->with('groupclassSections')->whereNULL('parent_id')->where('id',$request->id)->first();
            $sectionIds = SmClassSection::where('class_id', '=', $request->id)
                                          ->where('school_id', Auth::user()->school_id)->get();
            $promote_sections = [];
            foreach ($sectionIds as $sectionId) {
                $promote_sections[] = SmSection::where('id', $sectionId->section_id)->withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->whereNull('parent_id')->first(['id','section_name']);
            }
        }

        else{
            $class = SmClass::find($request->id);
            if (teacherAccess()) {
                $sectionIds = SmAssignSubject::where('class_id', '=', $request->id)
                ->where('teacher_id', Auth::user()->staff->id)
                ->where('school_id', Auth::user()->school_id)
                ->where('academic_id', getAcademicId())
                ->select('class_id','section_id')
                ->distinct(['class_id','section_id'])
                ->withoutGlobalScope(StatusAcademicSchoolScope::class)
                ->get();
            } else {
                 $sectionIds = SmClassSection::where('class_id', $request->id)
                ->withoutGlobalScope(StatusAcademicSchoolScope::class)->get();
            }

            $promote_sections = [];
            foreach ($sectionIds as $sectionId) {
                $promote_sections[] = SmSection::where('id', $sectionId->section_id)->withoutGlobalScope(StatusAcademicSchoolScope::class)->first(['id','section_name']);
            }
        }
      

        return response()->json([$promote_sections]);
    }

    public function ajaxGetClass(Request $request)
    {
        $classes = SmClass::where('created_at', 'LIKE', $request->year . '%')->get();

        return response()->json([$classes]);
    }

    
    public function ajaxSelectStudent(Request $request)
    {
       
        $student_ids = SmStudentReportController::classSectionStudent($request);
        $students = SmStudent::whereIn('id', $student_ids)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get()->map(function($student){
            return [
                'id' => $student->id,
                'full_name' => $student->first_name. ' '. $student->last_name,
                'user_id' => $student->user_id
                ];
        })->toArray();
        return response()->json([$students]);
    }
    public function ajaxPromoteYear(Request $request)
    {
        $classes = SmClass::where('academic_id', $request->year)
                ->where('school_id',Auth::user()->school_id)
                ->withOutGlobalScope(StatusAcademicSchoolScope::class)
                ->get();

        return response()->json([$classes]);
    }

    public function ajaxSectionStudent(Request $request)
    {
        try {
            $class = SmClass::withOutGlobalScope(StatusAcademicSchoolScope::class)->find($request->id);
            
            if (teacherAccess()) {
                $sectionIds = SmAssignSubject::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('class_id', '=', $request->id)
                            ->where('teacher_id',Auth::user()->staff->id)               
                            ->where('school_id', Auth::user()->school_id)
                            ->when($class, function ($q) use ($class) {
                                $q->where('academic_id', $class->academic_id);
                            })
                            ->select('class_id','section_id')
                            ->distinct(['class_id','section_id'])
                            ->get();
            } else {
                $sectionIds = SmClassSection::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('class_id', '=', $request->id) 
                                    ->when($class, function ($q) use ($class) {
                                        $q->where('academic_id', $class->academic_id);
                                    })              
                            ->where('school_id', Auth::user()->school_id)
                            ->get();
            }
            $sections = [];
            foreach ($sectionIds as $sectionId) {
                $section = SmSection::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('id',$sectionId->section_id)->select('id','section_name')->first();
                if($section){
                    $sections[] = $section;
                }
            }

            if(!($class)){
                $class = SmClass::withOutGlobalScope(GlobalAcademicScope::class)->withOutGlobalScope(StatusAcademicSchoolScope::class)->find($request->id);
                $sectionIds = SmClassSection::withOutGlobalScope(GlobalAcademicScope::class)->withOutGlobalScope(StatusAcademicSchoolScope::class)
                            ->where('class_id', $request->id) 
                            ->where('school_id', Auth::user()->school_id)
                            ->get();
                            $sections = [];
                    foreach ($sectionIds as $sectionId) 
                        {
                            $section = SmSection::withOutGlobalScope(GlobalAcademicScope::class)->withOutGlobalScope(StatusAcademicSchoolScope::class)->where('id',$sectionId->section_id)->select('id','section_name')->first();
                                if($section){
                                    $sections[] = $section;
                                }
                        }

            }
            return response()->json([$sections]);
        } catch (\Exception $e) {

            return response()->json("", 404);
        }
    }

    public function ajaxSubjectSection(Request $request)
    {
        
        if (teacherAccess()) {
            $sectionIds = SmAssignSubject::where('class_id', '=', $request->class_id)
            ->where('subject_id', '=', $request->subject_id)
            ->where('teacher_id',Auth::user()->staff->id)               
            ->where('school_id', Auth::user()->school_id)
            ->get();
        } else {
            $sectionIds = SmAssignSubject::where('class_id', '=', $request->class_id)
            ->where('subject_id', '=', $request->subject_id)               
            ->where('school_id', Auth::user()->school_id)
            ->get();
        }
        
        $promote_sections = [];
        foreach ($sectionIds as $sectionId) {
            if($request->globalType ){
                $promote_sections[] = SmSection::withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class)->where('id',$sectionId->section_id)->first(['id','section_name']);
            }else{
                $promote_sections[] = SmSection::where('id',$sectionId->section_id)->first(['id','section_name']);
            }
           
        }

        return response()->json([$promote_sections]);
    }

    public function ajaxSubjectFromExamType()
    {
        try {
            $subjects = SmExamSetup::with('subjectDetails')
                    ->where('exam_term_id', request()->id)
                    ->distinct('subject_id')->get();
            return response()->json([$subjects]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function getClasAcademicyear(Request $request)
    {
        $classes = [];
        $academic_years = SmAcademicYear::where('school_id', $request->id)->get();
        return response()->json([$classes, $academic_years]);
    }

     // Get class for regular school and saas for new student registration
     public function getClasses(Request $request)
     {
         $academic_year = SmAcademicYear::where('id', $request->id)->first();
       
         if ($academic_year) {
             $classes = SmClass::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('active_status', '=', '1')->where('academic_id', $request->id)->where('school_id', $academic_year->school_id)->get();
         
         } else {
             $school = app('school');
             $classes = SmClass::where('active_status', '=', '1')->where('academic_id', $request->id)->where('school_id', $school->id)->get();
         }
         return response()->json([$classes, $academic_year]);
     }

       // Get section for new registration by ajax
    public function getSection(Request $request)
    {
        try {
            $sectionIds = SmClassSection::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('class_id', '=', $request->id)->get();
            $sections = [];
            foreach ($sectionIds as $sectionId) {
                $sections[] = SmSection::withOutGlobalScope(StatusAcademicSchoolScope::class)->find($sectionId->section_id);
            }
            return response()->json($sections);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

}
