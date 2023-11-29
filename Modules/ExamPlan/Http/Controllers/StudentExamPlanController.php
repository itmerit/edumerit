<?php

namespace Modules\ExamPlan\Http\Controllers;

use App\SmExam;
use App\SmStudent;
use App\SmExamSchedule;
use App\SmAssignSubject;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\ExamPlan\Entities\AdmitCard;
use Illuminate\Contracts\Support\Renderable;
use Modules\ExamPlan\Entities\AdmitCardSetting;

class StudentExamPlanController extends Controller
{
    public function admitCard()
    {
        try{
            $student = Auth::user()->student;
            $records = StudentRecord::where('is_promote',0)
                                    ->where('student_id',$student->id)
                                    ->where('academic_id',getAcademicId())
                                    ->where('school_id',Auth::user()->school_id)
                                    ->get();
            return view('examplan::studentAdmitCard',compact('records'));
        }
        catch(\Exception $e){
            Toastr::error('Operation Failed','Error');
            return redirect()->back();
        }

    }

    public function admitCardSearch(Request $request)
    {
        try{
            $smExam = SmExam::findOrFail($request->exam);
            if(auth()->user()->role_id == 3){
                $student = SmStudent::find($request->student_id);
            }else{
                $student = Auth::user()->student;
            }
            $studentRecord =StudentRecord::where('student_id',$student->id)
                                            ->where('class_id',$smExam->class_id)
                                            ->where('section_id',$smExam->section_id)
                                            ->where('school_id',Auth::user()->school_id)
                                            ->where('academic_id',getAcademicId())
                                            ->first();

            $exam_routines = SmExamSchedule::where('class_id', $smExam->class_id)
                                            ->where('section_id', $smExam->section_id)
                                            ->where('exam_term_id', $smExam->exam_type_id)
                                            ->orderBy('date', 'ASC')
                                            ->get();
            if($exam_routines){
                
                $admit = AdmitCard::where('academic_id',getAcademicId())
                                    ->where('student_record_id', $studentRecord->id)
                                    ->where('exam_type_id', $smExam->exam_type_id)
                                    ->first();
                if($admit){
                return redirect()->route('examplan.admitCardDownload',$admit->id);
                }else{
                    Toastr::warning('Admit Card Not Pulished Yet','Warning');
                    return redirect()->back();
                }                    
            }else{
                Toastr::warning('Exam Routine Not Pulished Yet','Warning');
                return redirect()->back();
            }

        }
        catch( \Exception $e){
            Toastr::error('Operation Failed','Error');
            return redirect()->back();
        }

    }

    public function admitCardDownload($id)
    {
        try{

            $admit = AdmitCard::find($id);
            $studentRecord = StudentRecord::find($admit->student_record_id);
            $student = SmStudent::find($studentRecord->student_id);
            $setting = AdmitCardSetting::where('school_id',Auth::user()->school_id)
                                         ->where('academic_id',getAcademicId())   
                                        ->first();
            $assign_subjects = SmAssignSubject::where('class_id', $studentRecord->class_id)->where('section_id', $studentRecord->section_id)
                                        ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_routines = SmExamSchedule::where('class_id', $studentRecord->class_id)
                                        ->where('section_id', $studentRecord->section_id)
                                        ->where('exam_term_id', $admit->exam_type_id)->orderBy('date', 'ASC')->get();
           
            if($setting->admit_layout == 1){
                return view('examplan::studentAdmitCardDownload',compact('setting','assign_subjects','exam_routines','studentRecord','student','admit'));
            }else{
                return view('examplan::studentAdmitCardDownload_two',compact('setting','assign_subjects','exam_routines','studentRecord','student','admit'));
            }
        }
        catch(\Exception $e){
            Toastr::error('Operation Failed','Error');
            return redirect()->back();
        }
    }

    public function admitCardParent($student_id){
        try{
            $records = StudentRecord::where('is_promote',0)
            ->where('student_id',$student_id)
            ->where('academic_id',getAcademicId())
            ->where('school_id',Auth::user()->school_id)
            ->get();
            return view('examplan::studentAdmitCard',compact('records' ,'student_id'));
        }
        catch(\Exception $e){
            Toastr::error('Operation Failed','Error');
            return redirect()->back();
        }
    }




}
