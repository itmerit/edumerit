<?php

namespace App\Http\Controllers\Admin\Report;

use App\SmExam;
use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmExamType;
use App\SmExamSetup;
use App\SmMarkStore;
use App\SmMarksGrade;
use App\SmResultStore;
use App\SmAssignSubject;
use App\CustomResultSetting;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmClassOptionalSubject;
use App\SmOptionalSubjectAssign;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\University\Entities\UnFaculty;
use Modules\University\Entities\UnSession;
use Modules\University\Entities\UnSubject;
use Modules\University\Entities\UnSemester;
use Modules\University\Entities\UnDepartment;
use Modules\University\Entities\UnAcademicYear;
use Modules\University\Entities\UnAssignSubject;
use Modules\University\Entities\UnSemesterLabel;
use Modules\University\Entities\UnSubjectAssignStudent;
use App\Http\Requests\Admin\Reports\FinalMarkSheetRequest;
use App\Http\Requests\Admin\Reports\SubjectMarkSheetRequest;
use Modules\University\Http\Controllers\ExamCommonController;

class SubjectMarkSheetReportController extends Controller
{
    public function index(){
        try {
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
            return view('backEnd.examination.subjectMarkSheet', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function search(SubjectMarkSheetRequest $request){
       try{
        $class = SmClass::find($request->class);
        $data = [];
        $section = null;
        if(moduleStatusCheck('University')){
            $subject = UnSubject::find($request->un_subject_id);
            $assigned_subject = UnAssignSubject::where('school_id',Auth::user()->school_id)
                                                ->where('un_subject_id',$request->un_subject_id)
                                                ->where('un_semester_label_id',$request->un_semester_label_id)
                                                ->get();
                                                
        }else{
            $subject = SmSubject::find($request->subject);
            if($request->section){
                $section = SmSection::find($request->section);
            }

            $assigned_subject = SmAssignSubject::when($request->class, function ($query) use ($request) {
                $query->where('class_id', $request->class);
            })
            ->when($request->section, function ($query) use ($request) {
                $query->where('section_id', $request->section);
            })
            ->when($request->subject, function ($query) use ($request) {
                $query->where('subject_id', $request->subject);
            })
            ->where('school_id',Auth()->user()->school_id)
            ->where('academic_id',getAcademicId())
            ->get();

        }
       
        if($assigned_subject){
            if(moduleStatusCheck('University')){
                $sm_mark_stores = SmResultStore::where('un_semester_label_id',$request->un_semester_label_id)
                ->where('un_session_id',$request->un_session_id)
                ->where('school_id',Auth()->user()->school_id)
                ->where('un_academic_id',$request->un_academic_id)
                ->with('studentInfo')
                ->get()
                ->groupBy('student_id');
            }else{
                $sm_mark_stores = SmResultStore::when($request->class, function ($query) use ($request) {
                    $query->where('class_id', $request->class);
                })
                ->when($request->section, function ($query) use ($request) {
                    $query->where('section_id', $request->section);
                }) 
                ->when($request->subject, function ($query) use ($request) {
                    $query->where('subject_id', $request->subject);
                })
                ->where('school_id',Auth()->user()->school_id)
                ->where('academic_id',getAcademicId())
                ->with('studentInfo')
                ->get()
                ->distinct('student_id');
            }

            $students = StudentRecord::query();
            if(moduleStatusCheck('University')){
                $data['session'] = UnSession::find($request->un_session_id)->name;
                $data['academic_year'] = UnAcademicYear::find($request->un_academic_id)->name;
                $data['faculty'] = UnFaculty::find($request->un_faculty_id)->name;
                $data['department'] = UnDepartment::find($request->un_department_id)->name;
                $data['semester'] = UnSemester::find($request->un_semester_id)->name;
                $data['semester_label'] = UnSemesterLabel::find($request->un_semester_label_id)->name;
                $data['requestData'] = $request->all();
                $assignSubjects = UnSubjectAssignStudent::query();
                $students = unFilterBySub($assignSubjects, $request)
                            ->whereHas('studentDetail', function ($q)  {
                                $q->where('active_status', 1);
                            });
                $result_setting = CustomResultSetting::where('un_academic_id',getAcademicId())->where('school_id',Auth()->user()->school_id)->get();
            }else{
                $students = $students->when($request->class, function ($query) use ($request) {
                    $query->where('class_id', $request->class);
                    })
                    ->when($request->section, function ($query) use ($request) {
                        $query->where('section_id', $request->section);
                    })
                    ->where('academic_id',getAcademicId());
                    $result_setting = CustomResultSetting::where('academic_id',getAcademicId())->where('school_id',Auth()->user()->school_id)->get();
            }
                 
             $students->whereHas('studentDetail', function ($q)  {
                                            $q->where('active_status', 1);
                                        })->with('studentDetail')->get();
                                        
            $students = $students->get();
            $student_collection = collect();
            foreach($students as $student){
                $item = [
                    'student_name' => $student->studentDetail->full_name,
                    'admission_no' => $student->studentDetail->admission_no,
                    'roll_no' => $student->studentDetail->roll_no,
                    'avg_mark' => 0
                ];
                $examTypeMarks = collect();
                if(moduleStatusCheck('University')){
                    if(count($result_setting) > 0){
                        foreach($result_setting as $exam){
                            $signle_mark = singleSubjectMark($student->student_record_id,$subject->id,$exam->exam_type_id)[0];
                            $examTypeMarks->push(collect(['single_avg_mark' => $signle_mark]));     
                        }
                    }else{
                        foreach(examTypes() as $exam){
                            $signle_mark = singleSubjectMark($student->student_record_id,$subject->id,$exam->id,true)[0];
                            $examTypeMarks->push(collect(['single_avg_mark' => $signle_mark]));     
                        }
                    }

                }else{
                    if(count($result_setting) > 0){
                        foreach($result_setting as $exam){
                            $signle_mark = singleSubjectMark($student->id,$subject->id,$exam->exam_type_id)[0];
                            $examTypeMarks->push(collect(['single_avg_mark' => $signle_mark]));     
                        }
                    }else{
                        foreach(examTypes() as $exam){
                            $signle_mark = singleSubjectMark($student->id,$subject->id,$exam->id,true)[0];
                            $examTypeMarks->push(collect(['single_avg_mark' => $signle_mark]));     
                        }
                    }
                }
                $item['avg_mark'] = subjectAverageMark($student->id,$subject->id)[0];;
                $item['examTypeMarks'] = $examTypeMarks;
                $student_collection->push(collect($item));
            }

             $finalMarkSheets =  $student_collection->sortByDesc('avg_mark');
             
            if(is_null($sm_mark_stores)){
                Toastr::error('Mark Register Uncomplete', 'Failed');
                return redirect()->back();
            }

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

            return view('backEnd.examination.subjectMarkSheetList',compact('classes','sm_mark_stores','result_setting','students','subject','class','section','finalMarkSheets','data'));
        }

       }
       catch(\Exception $e){
        Toastr::error('Operation Failed', 'Failed');
        return redirect('subject_mark_sheet');
       } 
    }


    public function print(Request $request){
        try{
         $class = SmClass::find($request->class);
         $data = [];
         $section = null;
         if(moduleStatusCheck('University')){
             $subject = UnSubject::find($request->un_subject_id);
             $assigned_subject = UnAssignSubject::where('school_id',Auth::user()->school_id)
                                                 ->where('un_subject_id',$request->un_subject_id)
                                                 ->where('un_semester_label_id',$request->un_semester_label_id)
                                                 ->get();
                                                 
         }else{
             $subject = SmSubject::find($request->subject);
             if($request->section){
                 $section = SmSection::find($request->section);
             }
 
             $assigned_subject = SmAssignSubject::when($request->class, function ($query) use ($request) {
                 $query->where('class_id', $request->class);
             })
             ->when($request->section, function ($query) use ($request) {
                 $query->where('section_id', $request->section);
             })
             ->when($request->subject, function ($query) use ($request) {
                 $query->where('subject_id', $request->subject);
             })
             ->where('school_id',Auth()->user()->school_id)
             ->where('academic_id',getAcademicId())
             ->get();
         }
        
         if($assigned_subject){
             if(moduleStatusCheck('University')){
                 $sm_mark_stores = SmResultStore::where('un_semester_label_id',$request->un_semester_label_id)
                 ->where('un_session_id',$request->un_session_id)
                 ->where('school_id',Auth()->user()->school_id)
                 ->where('un_academic_id',$request->un_academic_id)
                 ->with('studentInfo')
                 ->get()
                 ->groupBy('student_id');
             }else{
                 $sm_mark_stores = SmResultStore::when($request->class, function ($query) use ($request) {
                     $query->where('class_id', $request->class);
                 })
                 ->when($request->section, function ($query) use ($request) {
                     $query->where('section_id', $request->section);
                 }) 
                 ->when($request->subject, function ($query) use ($request) {
                     $query->where('subject_id', $request->subject);
                 })
                 ->where('school_id',Auth()->user()->school_id)
                 ->where('academic_id',getAcademicId())
                 ->with('studentInfo')
                 ->get()
                 ->distinct('student_id');
             }
 
             $students = StudentRecord::query();
             if(moduleStatusCheck('University')){
                 $data['session'] = UnSession::find($request->un_session_id)->name;
                 $data['academic_year'] = UnAcademicYear::find($request->un_academic_id)->name;
                 $data['faculty'] = UnFaculty::find($request->un_faculty_id)->name;
                 $data['department'] = UnDepartment::find($request->un_department_id)->name;
                 $data['semester'] = UnSemester::find($request->un_semester_id)->name;
                 $data['semester_label'] = UnSemesterLabel::find($request->un_semester_label_id)->name;
                 $data['requestData'] = $request->all();
                 $result_setting = CustomResultSetting::where('school_id',Auth()->user()->school_id)->where('un_academic_id',getAcademicId())->get();
                 $assignSubjects = UnSubjectAssignStudent::query();
                 $students = unFilterBySub($assignSubjects, $request)
                            ->whereHas('studentDetail', function ($q)  {
                                $q->where('active_status', 1);
                            });
             }else{
                $result_setting = CustomResultSetting::where('school_id',Auth()->user()->school_id)->where('academic_id',getAcademicId())->get();
                 $students = $student->when($request->class, function ($query) use ($request) {
                     $query->where('class_id', $request->class);
                     })
                     ->when($request->section, function ($query) use ($request) {
                         $query->where('section_id', $request->section);
                     })
                     ->where('academic_id',getAcademicId());
             }
                  
             $students->whereHas('studentDetail', function ($q)  {
                            $q->where('active_status', 1);
                        })->with('studentDetail')->get();
                                         
             $students = $students->get(); 
             $student_collection = collect();
             foreach($students as $student){
                 $item = [
                     'student_name' => $student->studentDetail->full_name,
                     'admission_no' => $student->studentDetail->admission_no,
                     'roll_no' => $student->studentDetail->roll_no,
                     'avg_mark' => 0
                 ];
                 $examTypeMarks = collect();
                 if(moduleStatusCheck('University')){
                    if(count($result_setting) > 0){
                        foreach($result_setting as $exam){
                            $signle_mark = singleSubjectMark($student->student_record_id,$subject->id,$exam->exam_type_id)[0];
                            $examTypeMarks->push(collect(['single_avg_mark' => $signle_mark]));     
                        }
                    }else{
                        foreach(examTypes() as $exam){
                            $signle_mark = singleSubjectMark($student->student_record_id,$subject->id,$exam->id,true)[0];
                            $examTypeMarks->push(collect(['single_avg_mark' => $signle_mark]));     
                        }
                    }

                }else{
                    if(count($result_setting) > 0){
                        foreach($result_setting as $exam){
                            $signle_mark = singleSubjectMark($student->id,$subject->id,$exam->exam_type_id)[0];
                            $examTypeMarks->push(collect(['single_avg_mark' => $signle_mark]));     
                        }
                    }else{
                        foreach(examTypes() as $exam){
                            $signle_mark = singleSubjectMark($student->id,$subject->id,$exam->id,true)[0];
                            $examTypeMarks->push(collect(['single_avg_mark' => $signle_mark]));     
                        }
                    }
                }
                 $item['avg_mark'] = subjectAverageMark($student->id,$subject->id)[0];;
                 $item['examTypeMarks'] = $examTypeMarks;
                 $student_collection->push(collect($item));
             }
             $finalMarkSheets =  $student_collection->sortByDesc('avg_mark');
             if(is_null($sm_mark_stores)){
                 Toastr::error('Mark Register Uncomplete', 'Failed');
                 return redirect()->back();
             }
 
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

            $grades = SmMarksGrade::where('school_id', Auth::user()->school_id)
             ->orderBy('gpa', 'desc')
             ->where('academic_id',getAcademicId())
             ->get(); 
 
             if(moduleStatusCheck('University')){
                return view('university::exam.un_subject_mark_sheet_print',compact('classes','sm_mark_stores','result_setting','students','subject','class','section','grades','finalMarkSheets','data','grades'));
             }else{
                return view('backEnd.examination.subjectMarkSheetPrint',compact('classes','sm_mark_stores','result_setting','students','subject','class','section','grades','finalMarkSheets','data'));
             }
             
         }
 
        }
        catch(\Exception $e){
           
         Toastr::error('Operation Failed', 'Failed');
         return redirect('subject_mark_sheet');
        } 
     }


    // public function print(Request $request){
    //     try{
           
    //      $class = SmClass::find($request->class);
    //      $subject = SmSubject::find($request->subject);
    //      $section = null;
    //      if($request->section){
    //          $section = SmSection::find($request->section);
    //      }
    //      $assigned_subject = SmAssignSubject::when($request->class, function ($query) use ($request) {
    //          $query->where('class_id', $request->class);
    //      })
    //      ->when($request->section, function ($query) use ($request) {
    //          $query->where('section_id', $request->section);
    //      })
    //      ->when($request->subject, function ($query) use ($request) {
    //          $query->where('subject_id', $request->subject);
    //      })
    //      ->where('school_id',Auth()->user()->school_id)
    //      ->where('academic_id',getAcademicId())
    //      ->get();
         
    //      if($assigned_subject){
 
    //          $sm_mark_stores = SmResultStore::when($request->class, function ($query) use ($request) {
    //              $query->where('class_id', $request->class);
    //          })
    //          ->when($request->section, function ($query) use ($request) {
    //              $query->where('section_id', $request->section);
    //          }) 
    //          ->when($request->subject, function ($query) use ($request) {
    //              $query->where('subject_id', $request->subject);
    //          })
    //          ->where('school_id',Auth()->user()->school_id)
    //          ->where('academic_id',getAcademicId())
    //          ->with('studentInfo')
    //          ->get()->distinct('student_id');
 
    //          $students = StudentRecord::when($request->class, function ($query) use ($request) {
    //                                      $query->where('class_id', $request->class);
    //                                      })
    //                                      ->when($request->section, function ($query) use ($request) {
    //                                          $query->where('section_id', $request->section);
    //                                      })->where('school_id',Auth()->user()->school_id)
    //                                      ->where('academic_id',getAcademicId())
    //                                      ->where('is_promote',0)
    //                                      ->whereHas('studentDetail', function ($q)  {
    //                                          $q->where('active_status', 1);
    //                                      })->with('studentDetail')->get();  
    //          $result_setting = CustomResultSetting::where('school_id',Auth()->user()->school_id)
    //          ->where('academic_id',getAcademicId())
    //          ->get();
    //          if(is_null($sm_mark_stores)){
    //              Toastr::error('Mark Register Uncomplete', 'Failed');
    //              return redirect()->back();
    //          }
 
    //          if (teacherAccess()) {
    //              $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
    //              $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
    //                  ->where('sm_assign_subjects.academic_id', getAcademicId())
    //                  ->where('sm_assign_subjects.active_status', 1)
    //                  ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
    //                  ->select('sm_classes.id','class_name')
    //                  ->distinct('sm_classes.id')
    //                  ->get();
    //          } else {
    //              $classes = SmClass::where('active_status', 1)
    //                  ->where('academic_id', getAcademicId())
    //                  ->where('school_id',Auth::user()->school_id)
    //                  ->get();
    //          }

    //          $grades = SmMarksGrade::where('school_id', Auth::user()->school_id)
    //          ->where('academic_id', getAcademicId())
    //          ->orderBy('gpa', 'desc')
    //          ->get(); 
             
    //          $student_collection = collect();
    //          foreach($students as $student){
    //              $item = [
    //                  'student_name' => $student->studentDetail->full_name,
    //                  'admission_no' => $student->studentDetail->admission_no,
    //                  'roll_no' => $student->studentDetail->roll_no,
    //                  'avg_mark' => 0
    //              ];
    //              $examTypeMarks = collect();
                 
    //              if(count($result_setting) > 0){
    //                 foreach($result_setting as $exam){
    //                     $signle_mark = singleSubjectMark($student->id,$subject->id,$exam->exam_type_id)[0];
    //                     $examTypeMarks->push(collect(['single_avg_mark' => $signle_mark]));     
    //                 }
    //             }else{
    //                 foreach(examTypes() as $exam){
    //                     $signle_mark = singleSubjectMark($student->id,$subject->id,$exam->id,true)[0];
    //                     $examTypeMarks->push(collect(['single_avg_mark' => $signle_mark]));     
    //                 }
    //             }
    //              $item['avg_mark'] = subjectAverageMark($student->id,$subject->id)[0];;
    //              $item['examTypeMarks'] = $examTypeMarks;
    //              $student_collection->push(collect($item));
 
    //          }
 
    //          $finalMarkSheets =  $student_collection->sortByDesc('avg_mark');
 
    //          return view('backEnd.examination.subjectMarkSheetPrint',compact('classes','sm_mark_stores','result_setting','students','subject','class','section','grades','finalMarkSheets'));
    //      }
 
    //     }
    //     catch(\Exception $e){
    //         Toastr::error('Operation Failed', 'Failed');
    //         return redirect('subject_mark_sheet');
    //     } 
    //  }

     public function finalMarkSheet(){

        try{
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

            return view('backEnd.examination.finalMarkSheet',compact('classes'));
        }
        catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect('final_mark_sheet');
        }
     }

     public function finalMarkSheetSearch(FinalMarkSheetRequest $request){
        try{
            $class = SmClass::find($request->class);
            $section = null;
            if($request->section){
                $section = SmSection::find($request->section);
            }
           
           if(moduleStatusCheck('University')){

                $result_setting = CustomResultSetting::where('school_id',Auth()->user()->school_id)
                ->where('un_academic_id',getAcademicId())
                ->get();

                if($request->un_section_id){
                    $section = SmSection::find($request->un_section_id);
                }
                $data['session'] = UnSession::find($request->un_session_id)->name;
                $data['academic_year'] = UnAcademicYear::find($request->un_academic_id)->name;
                $data['faculty'] = UnFaculty::find($request->un_faculty_id)->name;
                $data['department'] = UnDepartment::find($request->un_department_id)->name;
                $data['semester'] = UnSemester::find($request->un_semester_id)->name;
                $data['semester_label'] = UnSemesterLabel::find($request->un_semester_label_id)->name;
                $data['requestData'] = $request->all();
                $assigned_subjects = UnAssignSubject::where('un_semester_label_id',$request->un_semester_label_id)->get()->unique('un_subject_id');
           }else{
                   
                
                $result_setting = CustomResultSetting::where('school_id',Auth()->user()->school_id)
                ->where('academic_id',getAcademicId())
                ->get();
                    $assigned_subjects = SmAssignSubject::where('school_id',Auth()->user()->school_id)
                                                        ->when($request->class, function ($query) use ($request) {
                                                            $query->where('class_id', $request->class);
                                                        })->when($request->section, function ($query) use ($request) {
                                                                    $query->where('section_id', $request->section);
                                                        })->where('academic_id',getAcademicId())->get()->unique('subject_id');    
           }

            if(is_null($assigned_subjects)){
                Toastr::error('Subject Not Assigned', 'Failed');
                return redirect()->back();
            }

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

            $result = SmResultStore::query();
            $result->where('school_id',Auth()->user()->school_id);
            if(moduleStatusCheck('University')){
                $result = universityFilter($result,$request);
            }else{
                $result->when($request->class, function ($query) use ($request) {
                    $query->where('class_id', $request->class);
                })
                ->when($request->section, function ($query) use ($request) {
                    $query->where('section_id', $request->section);
                })->where('academic_id',getAcademicId());
            }
            
            $result = $result->with('studentInfo')->get()->distinct('student_id');

            if($result){
                $students = StudentRecord::query();
                $students->where('school_id',Auth()->user()->school_id);
                if(moduleStatusCheck('University')){
                    $students = universityFilter($students, $request);
                }else{
                    $students = $students->when($request->class, function ($query) use ($request) {
                        $query->where('class_id', $request->class);
                        })
                        ->when($request->section, function ($query) use ($request) {
                        $query->where('section_id', $request->section);
                        })->where('academic_id',getAcademicId())->where('is_promote',0);
                }
                
                $students = $students->whereHas('studentDetail', function ($q)  {
                            $q->where('active_status', 1);
                        })->with('studentDetail')->get();
                        
                        $student_collection = collect();
                        foreach($students as $student){
                            $item = [
                                'student_name' => $student->studentDetail->full_name,
                                'admission_no' => $student->studentDetail->admission_no,
                                'roll_no' => $student->studentDetail->roll_no,
                                'avg_mark' => 0
                            ];
                            $subjects = collect();
                            $all_subject_ids = [];
                            $s_id = [];
                            $subject_total = 0;
                            foreach($assigned_subjects as $assigned_subject){
                                if(moduleStatusCheck('University')){
                                    $student_assigned_sub = UnSubjectAssignStudent::where('student_record_id',$student->id)->get('un_subject_id');
                                    foreach($student_assigned_sub as $sub){
                                        $s_id[] = $sub->un_subject_id;
                                    }
                                    if( in_array($assigned_subject->un_subject_id, $s_id)){
                                        $signle_mark = subjectAverageMark($student->id,$assigned_subject->un_subject_id)[0];
                                        $all_subject_ids[] = $assigned_subject->un_subject_id ;
                                        $subject_total += $signle_mark;
                                    }else{
                                        $signle_mark = "-";
                                    }
                                    
                                }else{
                                    $signle_mark = subjectAverageMark($student->id,$assigned_subject->subject_id)[0];
                                    $all_subject_ids[] = $assigned_subject->subject_id ;
                                }
                                $subjects->push(collect(['exam_mark' => $signle_mark ]));
                                   
                            }
                            $item['avg_mark'] = $subject_total/count($all_subject_ids);
                            // $subjects->avg('exam_mark');
                            $item['subjects'] = $subjects;
                            $student_collection->push(collect($item));

                        }

                        $finalMarkSheets =  $student_collection->sortByDesc('avg_mark');
                      
                    return view('backEnd.examination.finalMarkSheetList',compact('classes','students','class','section','assigned_subjects','result_setting','finalMarkSheets','all_subject_ids','data'));
                }
    
            else{
                Toastr::error('Mark Register Uncomplete', 'Failed');
                return redirect('final_mark_sheet');
               
            }
        }
            
        catch( \Exception $e){
         
            Toastr::error('Operation Failed', 'Failed');
            return redirect('final_mark_sheet');
        }
     }


    //  $grades = SmMarksGrade::where('school_id', Auth::user()->school_id)
    //                     ->where('academic_id', getAcademicId())
    //                     ->orderBy('gpa', 'desc')
    //                     ->get(); 
    //return view('backEnd.examination.finalMarkSheetPrint',compact('classes','students','class','section','assigned_subjects','result_setting','finalMarkSheets','grades','all_subject_ids'));

     public function finalMarkSheetPrint(Request $request){
        {
            try{
                $class = SmClass::find($request->class);
                $section = null;
                if($request->section){
                    $section = SmSection::find($request->section);
                }
               
               if(moduleStatusCheck('University')){
                    $result_setting = CustomResultSetting::where('school_id',Auth()->user()->school_id)
                    ->where('un_academic_id',getAcademicId())
                    ->get();

                    if($request->un_section_id){
                        $section = SmSection::find($request->un_section_id);
                    }
                    $data['session'] = UnSession::find($request->un_session_id)->name;
                    $data['academic_year'] = UnAcademicYear::find($request->un_academic_id)->name;
                    $data['faculty'] = UnFaculty::find($request->un_faculty_id)->name;
                    $data['department'] = UnDepartment::find($request->un_department_id)->name;
                    $data['semester'] = UnSemester::find($request->un_semester_id)->name;
                    $data['semester_label'] = UnSemesterLabel::find($request->un_semester_label_id)->name;
                    $data['requestData'] = $request->all();
                    $assigned_subjects = UnAssignSubject::where('un_semester_label_id',$request->un_semester_label_id)->get()->unique('un_subject_id');
               }else{
                       
                    
                        $result_setting = CustomResultSetting::where('school_id',Auth()->user()->school_id)
                        ->where('academic_id',getAcademicId())
                        ->get();
                        $assigned_subjects = SmAssignSubject::where('school_id',Auth()->user()->school_id)
                                                            ->when($request->class, function ($query) use ($request) {
                                                                $query->where('class_id', $request->class);
                                                            })->when($request->section, function ($query) use ($request) {
                                                                        $query->where('section_id', $request->section);
                                                            })->where('academic_id',getAcademicId())->get()->unique('subject_id');    
               }
    
                if(is_null($assigned_subjects)){
                    Toastr::error('Subject Not Assigned', 'Failed');
                    return redirect()->back();
                }
    
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
    
                $result = SmResultStore::query();
                $result->where('school_id',Auth()->user()->school_id);
                if(moduleStatusCheck('University')){
                    $result = universityFilter($result,$request);
                }else{
                    $result->when($request->class, function ($query) use ($request) {
                        $query->where('class_id', $request->class);
                    })
                    ->when($request->section, function ($query) use ($request) {
                        $query->where('section_id', $request->section);
                    })->where('academic_id',getAcademicId());
                }
                
                $result = $result->with('studentInfo')->get()->distinct('student_id');
    
                if($result){
                    $students = StudentRecord::query();
                    $students->where('school_id',Auth()->user()->school_id);
                    if(moduleStatusCheck('University')){
                        $students = universityFilter($students, $request);
                    }else{
                        $students = $students->when($request->class, function ($query) use ($request) {
                            $query->where('class_id', $request->class);
                            })
                            ->when($request->section, function ($query) use ($request) {
                            $query->where('section_id', $request->section);
                            })->where('academic_id',getAcademicId())->where('is_promote',0);
                    }
                    
                    $students = $students->whereHas('studentDetail', function ($q)  {
                                $q->where('active_status', 1);
                            })->with('studentDetail')->get();
                            
                            $student_collection = collect();
                            foreach($students as $student){
                                $item = [
                                    'student_name' => $student->studentDetail->full_name,
                                    'admission_no' => $student->studentDetail->admission_no,
                                    'roll_no' => $student->studentDetail->roll_no,
                                    'avg_mark' => 0
                                ];
                                $subjects = collect();
                                $all_subject_ids = [];
                                foreach($assigned_subjects as $assigned_subject){
                                    if(moduleStatusCheck('University')){
                                        $signle_mark = subjectAverageMark($student->id,$assigned_subject->un_subject_id)[0];
                                        $all_subject_ids[] = $assigned_subject->un_subject_id ;
                                    }else{
                                        $signle_mark = subjectAverageMark($student->id,$assigned_subject->subject_id)[0];
                                        $all_subject_ids[] = $assigned_subject->subject_id ;
                                    }
                                    $subjects->push(collect(['exam_mark' => $signle_mark ]));
                                       
                                }
                                $item['avg_mark'] = $subjects->avg('exam_mark');
                                $item['subjects'] = $subjects;
                                $student_collection->push(collect($item));
    
                            }
    
                        $finalMarkSheets =  $student_collection->sortByDesc('avg_mark');
                        $grades = SmMarksGrade::where('school_id', Auth::user()->school_id)
                                            ->where('academic_id', getAcademicId())
                                             ->orderBy('gpa', 'desc')
                                             ->get(); 
                        return view('backEnd.examination.finalMarkSheetPrint',compact('classes','students','class','section','assigned_subjects','result_setting','finalMarkSheets','grades','all_subject_ids','data'));
                    }
        
                else{
                    Toastr::error('Mark Register Uncomplete', 'Failed');
                    return redirect('final_mark_sheet');
                   
                }
            }
                
            catch( \Exception $e){
                Toastr::error('Operation Failed', 'Failed');
                return redirect('final_mark_sheet');
            }
         }
     
    }

     public function studentFinalMarkSheet(){
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
        
 
        return view('backEnd.examination.studentFinalMarkSheet',compact('classes'));

     }

     public function studentFinalMarkSheetSearch(Request $request){

        try{
            $data = [];
            if(moduleStatusCheck('University')){
                $data['session'] = UnSession::find($request->un_session_id)->name;
                $data['academic_year'] = UnAcademicYear::find($request->un_academic_id)->name;
                $data['faculty'] = UnFaculty::find($request->un_faculty_id)->name;
                $data['department'] = UnDepartment::find($request->un_department_id)->name;
                $data['semester'] = UnSemester::find($request->un_semester_id)->name;
                $data['semester_label'] = UnSemesterLabel::find($request->un_semester_label_id)->name;
                $data['requestData'] = $request->all();
                $exams = SmExam::where('active_status', 1)
                ->where('un_semester_label_id', $request->un_semester_label_id)
                ->where('un_session_id', $request->un_session_id)
                ->where('school_id',Auth::user()->school_id)
                ->get();

                $exam_types = SmExamType::where('active_status', 1)
                            ->where('un_academic_id', getAcademicId())
                            ->pluck('id');
                $fail_grade = SmMarksGrade::where('active_status',1)
                            ->where('school_id',Auth::user()->school_id)
                            ->min('gpa');

                $fail_grade_name = SmMarksGrade::where('active_status',1)
                                ->where('school_id',Auth::user()->school_id)
                                ->where('gpa',$fail_grade)
                                ->first();

                $studentDetails = StudentRecord::where('student_id', $request->student_id)
                                                ->where('un_semester_label_id', $request->un_semester_label_id)
                                                ->where('un_academic_id', $request->un_academic_id)
                                                ->where('school_id', Auth::user()->school_id)
                                                ->first();
                $marks_grade = SmMarksGrade::where('school_id', Auth::user()->school_id)
                                            ->where('un_academic_id',getAcademicId())
                                            ->orderBy('gpa', 'desc')
                                            ->get();
                    
                $maxGrade = SmMarksGrade::where('school_id',Auth::user()->school_id)
                                        ->max('gpa');
                $exam_setup = SmExamSetup::where([
                                            ['un_semester_label_id', $request->un_semester_label_id], 
                                            ['un_session_id', $request->un_session_id]])
                                            ->where('school_id',Auth::user()->school_id)
                                            ->get();

                $record_id = @$studentDetails->id;
                $studentSubjects = UnSubjectAssignStudent::where('student_record_id',$record_id)->get('un_subject_id');  
                if(count($studentSubjects) == 0 ){
                    Toastr::warning('No subject assigned', 'Failed');
                    return redirect()->back();
                }                     

                $examSubjectIds = [];
                foreach($studentSubjects as $studentSubject){
                    $examSubjectIds[] = $studentSubject->un_subject_id;
                }
                $subjects = UnAssignSubject::where('un_semester_label_id', $request->un_semester_label_id)
                            ->where('school_id',Auth::user()->school_id)
                            ->whereIn('un_subject_id', $examSubjectIds)
                            ->get();
                    $assinged_exam_types = [];
                    foreach ($exams as $exam) {
                        $assinged_exam_types[] = $exam->exam_type_id;
                    }
                $assinged_exam_types = array_unique($assinged_exam_types);
            
                $result_setting = CustomResultSetting::where('school_id',Auth()->user()->school_id)
                                    ->where('un_academic_id',getAcademicId())
                                    ->get();

                foreach ($assinged_exam_types as $assinged_exam_type) {
                    foreach ($subjects as $subject) {
                        $is_mark_available = SmResultStore::where([
                                            ['un_semester_label_id', $request->un_semester_label_id],  
                                            ['student_id', $request->student_id]
                                            ])
                                            ->first();
                                            
                        if ($is_mark_available == "") {
                            Toastr::error('Ops! Your result is not found! Please check mark register.', 'Failed');
                            return redirect('progress-card-report');
                        
                        }
                    }
                }
                $is_result_available = SmResultStore::where([
                    ['un_semester_label_id', $request->un_semester_label_id], ['un_section_id', $request->un_section_id], 
                    ['student_id', $request->student_id]
                    ])
                    ->get();
                    $student_id = $request->student_id;
                $all_subject_ids = array_unique($examSubjectIds);    
                if ($is_result_available->count() > 0) {
                        return view('university::exam.unStudentFinalMarkSheet', 
                        compact(
                        'exams',
                        'is_result_available', 
                        'subjects', 
                        'data',
                        'student_id', 
                        'studentDetails',
                        'exam_types', 
                        'assinged_exam_types',
                        'marks_grade',
                        'fail_grade_name',
                        'fail_grade',
                        'maxGrade',
                        'result_setting',
                        'record_id',
                        'all_subject_ids'
                    ));
                    } else {
                        Toastr::error('Ops! Your result is not found! Please check mark register.', 'Failed');
                        return redirect('student_mark_sheet_final');
                    }


            }else{
                $result_setting = CustomResultSetting::where('school_id',Auth()->user()->school_id)
                ->where('academic_id',getAcademicId())
                ->get();
                
                $exams = SmExam::where('active_status', 1)
                ->where('class_id', $request->class)
                ->where('section_id', $request->section)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();

                $exam_types = SmExamType::where('active_status', 1)
                            ->where('academic_id', getAcademicId())
                            ->where('school_id',Auth::user()->school_id)
                            ->pluck('id');
                
                

                $classes = SmClass::where('active_status', 1)
                            ->where('academic_id', getAcademicId())
                            ->where('school_id',Auth::user()->school_id)
                            ->get();

                $fail_grade = SmMarksGrade::where('active_status',1)
                            ->where('academic_id', getAcademicId())
                            ->where('school_id',Auth::user()->school_id)
                            ->min('gpa');

                $fail_grade_name = SmMarksGrade::where('active_status',1)
                                ->where('academic_id', getAcademicId())
                                ->where('school_id',Auth::user()->school_id)
                                ->where('gpa',$fail_grade)
                                ->first();

                $studentDetails = StudentRecord::where('student_id', $request->student)
                                    ->where('class_id', $request->class)
                                    ->where('section_id', $request->section)
                                    ->where('academic_id', getAcademicId())
                                    ->where('school_id', Auth::user()->school_id)
                                    ->first();

                $marks_grade = SmMarksGrade::where('school_id', Auth::user()->school_id)
                            ->where('academic_id', getAcademicId())
                            ->orderBy('gpa', 'desc')
                            ->get();

                $maxGrade = SmMarksGrade::where('academic_id', getAcademicId())
                            ->where('school_id',Auth::user()->school_id)
                            ->max('gpa');

                $optional_subject_setup = SmClassOptionalSubject::where('class_id','=',$request->class)
                                            ->first();

                $student_optional_subject = SmOptionalSubjectAssign::where('student_id',$request->student)
                                            ->where('session_id','=',$studentDetails->session_id)
                                            ->first();

                $exam_setup = SmExamSetup::where([
                            ['class_id', $request->class], 
                            ['section_id', $request->section]])
                            ->where('school_id',Auth::user()->school_id)
                            ->get();

                $class_id = $request->class;
                $section_id = $request->section;
                $student_id = $request->student;
                $record_id = StudentRecord::where('class_id',$class_id)
                                                ->where('section_id',$section_id)
                                                ->where('school_id',auth()->user()->school_id)
                                                ->where('academic_id',getAcademicId())
                                                ->where('student_id',$student_id)
                                                ->value('id');
                $examSubjects = SmExam::where([['section_id', $section_id], ['class_id', $class_id]])
                                        ->where('school_id',Auth::user()->school_id)
                                        ->where('academic_id',getAcademicId())
                                        ->get();

                $examSubjectIds = [];
                foreach($examSubjects as $examSubject){
                    $examSubjectIds[] = $examSubject->subject_id;
                }
                $subjects = SmAssignSubject::where([
                            ['class_id', $request->class], 
                            ['section_id', $request->section]])
                            ->where('school_id',Auth::user()->school_id)
                            ->whereIn('subject_id', $examSubjectIds)
                            ->get();

                $assinged_exam_types = [];
                foreach ($exams as $exam) {
                    $assinged_exam_types[] = $exam->exam_type_id;
                }
                $assinged_exam_types = array_unique($assinged_exam_types);
                foreach ($assinged_exam_types as $assinged_exam_type) {
                    foreach ($subjects as $subject) {
                        $is_mark_available = SmResultStore::where([
                                            ['class_id', $request->class], 
                                            ['section_id', $request->section], 
                                            ['student_id', $request->student]
                                            // ['exam_type_id', $assinged_exam_type]]
                                            ])
                                            ->first();
                        if ($is_mark_available == "") {
                            Toastr::error('Ops! Your result is not found! Please check mark register.', 'Failed');
                            return redirect('progress-card-report');
                        
                        }
                    }
                }
                $is_result_available = SmResultStore::where([
                    ['class_id', $request->class], 
                    ['section_id', $request->section], 
                    ['student_id', $request->student]])
                    ->where('school_id',Auth::user()->school_id)
                    ->get();

                    $all_subject_ids = array_unique($examSubjectIds);
                    

                if ($is_result_available->count() > 0) {
                    return view('backEnd.examination.studentFinalMarkSheet', 
                    compact(
                    'exams',
                    'optional_subject_setup',
                    'student_optional_subject', 
                    'classes', 'studentDetails',
                    'is_result_available', 
                    'subjects', 
                    'class_id', 
                    'section_id', 
                    'student_id', 
                    'exam_types', 
                    'assinged_exam_types',
                    'marks_grade',
                    'fail_grade_name',
                    'fail_grade',
                    'maxGrade',
                    'result_setting',
                    'record_id',
                    'all_subject_ids'
                ));
                } else {
                    Toastr::error('Ops! Your result is not found! Please check mark register.', 'Failed');
                    return redirect('student_mark_sheet_final');
                }
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect('student_mark_sheet_final');
        }
     }

     public function studentFinalMarkSheetPrint(Request $request){
        try{

            if(moduleStatusCheck('University')){
                $data['session'] = UnSession::find($request->un_session_id)->name;
                $data['academic_year'] = UnAcademicYear::find($request->un_academic_id)->name;
                $data['faculty'] = UnFaculty::find($request->un_faculty_id)->name;
                $data['department'] = UnDepartment::find($request->un_department_id)->name;
                $data['semester'] = UnSemester::find($request->un_semester_id)->name;
                $data['semester_label'] = UnSemesterLabel::find($request->un_semester_label_id)->name;
                $data['requestData'] = $request->all();
                $exams = SmExam::where('active_status', 1)
                ->where('un_semester_label_id', $request->un_semester_label_id)
                ->where('un_session_id', $request->un_session_id)
                ->where('school_id',Auth::user()->school_id)
                ->get();

                $exam_types = SmExamType::where('active_status', 1)
                            ->where('un_academic_id', getAcademicId())
                            ->pluck('id');
                $fail_grade = SmMarksGrade::where('active_status',1)
                            ->where('school_id',Auth::user()->school_id)
                            ->min('gpa');

                $fail_grade_name = SmMarksGrade::where('active_status',1)
                                ->where('school_id',Auth::user()->school_id)
                                ->where('gpa',$fail_grade)
                                ->first();

                $studentDetails = StudentRecord::where('student_id', $request->student_id)
                                                ->where('un_semester_label_id', $request->un_semester_label_id)
                                                ->where('un_academic_id', $request->un_academic_id)
                                                ->where('school_id', Auth::user()->school_id)
                                                ->first();
                $marks_grade = SmMarksGrade::where('school_id', Auth::user()->school_id)
                                            ->where('un_academic_id',getAcademicId())
                                            ->orderBy('gpa', 'desc')
                                            ->get();
                    
                $maxGrade = SmMarksGrade::where('school_id',Auth::user()->school_id)
                                        ->max('gpa');
                $exam_setup = SmExamSetup::where([
                                            ['un_semester_label_id', $request->un_semester_label_id], 
                                            ['un_session_id', $request->un_session_id]])
                                            ->where('school_id',Auth::user()->school_id)
                                            ->get();

                $record_id = @$studentDetails->id;
                $studentSubjects = UnSubjectAssignStudent::where('student_record_id',$record_id)->get('un_subject_id');  

                $examSubjectIds = [];
                foreach($studentSubjects as $studentSubject){
                    $examSubjectIds[] = $studentSubject->un_subject_id;
                }
            
                $subjects = UnAssignSubject::where('un_semester_label_id', $request->un_semester_label_id)
                            ->where('school_id',Auth::user()->school_id)
                            ->whereIn('un_subject_id', $examSubjectIds)
                            ->get();
                    $assinged_exam_types = [];
                    foreach ($exams as $exam) {
                        $assinged_exam_types[] = $exam->exam_type_id;
                    }
                $assinged_exam_types = array_unique($assinged_exam_types);
            
                $result_setting = CustomResultSetting::where('school_id',Auth()->user()->school_id)
                                    ->where('un_academic_id',getAcademicId())
                                    ->get();

                foreach ($assinged_exam_types as $assinged_exam_type) {
                    foreach ($subjects as $subject) {
                        $is_mark_available = SmResultStore::where([
                                            ['un_semester_label_id', $request->un_semester_label_id],  
                                            ['student_id', $request->student_id]
                                            ])
                                            ->first();
                                            
                        if ($is_mark_available == "") {
                            Toastr::error('Ops! Your result is not found! Please check mark register.', 'Failed');
                            return redirect('progress-card-report');
                        
                        }
                    }
                }
                $is_result_available = SmResultStore::where([
                    ['un_semester_label_id', $request->un_semester_label_id], ['un_section_id', $request->un_section_id], 
                    ['student_id', $request->student_id]
                    ])
                    ->get();
                    $student_id = $request->student_id;
                $all_subject_ids = array_unique($examSubjectIds);    
                if ($is_result_available->count() > 0) {
                        return view('university::exam.unStudentFinalMarkSheetPrint', 
                        compact(
                        'exams',
                        'is_result_available', 
                        'subjects', 
                        'data',
                        'student_id', 
                        'studentDetails',
                        'exam_types', 
                        'assinged_exam_types',
                        'marks_grade',
                        'fail_grade_name',
                        'fail_grade',
                        'maxGrade',
                        'result_setting',
                        'record_id',
                        'all_subject_ids'
                    ));
                    } else {
                        Toastr::error('Ops! Your result is not found! Please check mark register.', 'Failed');
                        return redirect('student_mark_sheet_final');
                    }


            }else{
                    $studentDetails = StudentRecord::where('class_id',$request->class_id)
                                ->where('section_id',$request->section_id)
                                ->where('academic_id',getAcademicId())
                                ->where('school_id',auth()->user()->school_id)
                                ->where('student_id',$request->student_id)
                                ->first();

                        $record = $studentDetails;  
                        $record_id = $record->id; 
                        $result_setting =  CustomResultSetting::where('school_id',Auth()->user()->school_id)
                        ->where('academic_id',getAcademicId())
                        ->get();  
                        $grades = SmMarksGrade::where('school_id', Auth::user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->orderBy('gpa', 'desc')
                        ->get();   
                        if($studentDetails){
                            $subjects = $studentDetails->assign_subject;
                            $all_subject_ids = $subjects->pluck('subject_id')->toArray();
                            $is_result_available = SmResultStore::where([
                                ['class_id', $studentDetails->class_id], 
                                ['section_id', $studentDetails->section_id], 
                                ['student_id', $studentDetails->student_id]])
                                ->where('school_id',Auth::user()->school_id)
                                ->get();
                        }
                        $student_detail = SmStudent::find($record->student_id);
                        if ($is_result_available->count() > 0) {
                            return view('backEnd.examination.studentFinalMarkSheetPrint',compact('subjects','studentDetails','all_subject_ids','is_result_available','record','record_id','result_setting','grades','student_detail')); 
                        }
                        else{
                            Toastr::warning('Result Not Completed', 'Failed');
                            return redirect('student_mark_sheet_final');
                        }
        }
        }
        catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect('student_mark_sheet_final');
        }
     }
}
