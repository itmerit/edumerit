<?php

namespace App\Http\Controllers\Admin\Examination;
use App\tableList;
use App\YearCheck;
use App\SmMarksGrade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Examination\SmMarkGradeRequest;

class SmMarksGradeController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try{
            $marks_grades = SmMarksGrade::orderBy('gpa', 'desc')->get();
            return view('backEnd.examination.marks_grade', compact('marks_grades'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(SmMarkGradeRequest $request)
    {
        try{
            $marks_grade = new SmMarksGrade();
            $marks_grade->grade_name = $request->grade_name;
            $marks_grade->gpa = $request->gpa;
            $marks_grade->percent_from = $request->percent_from;
            $marks_grade->percent_upto = $request->percent_upto;
            $marks_grade->from = $request->grade_from;
            $marks_grade->up = $request->grade_upto;
            $marks_grade->description = $request->description;
            $marks_grade->created_by=auth()->user()->id;
            $marks_grade->created_at= YearCheck::getYear() .'-'.date('m-d h:i:s');
            $marks_grade->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $marks_grade->un_academic_id = getAcademicId();
            }else{
                $marks_grade->academic_id = getAcademicId();
            }
            $result = $marks_grade->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
             ;
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function show(Request $request, $id)
    {
        try{
            $marks_grade = SmMarksGrade::find($id);
            if(moduleStatusCheck('University')){
                $marks_grades = SmMarksGrade::where('un_academic_id', getAcademicId())->get();
            }else{
                $marks_grades = SmMarksGrade::where('academic_id', getAcademicId())->get();
            }
            return view('backEnd.examination.marks_grade', compact('marks_grade', 'marks_grades'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(SmMarkGradeRequest $request, $id)
    {
        try{
            $marks_grade = SmMarksGrade::find($request->id);
            $marks_grade->grade_name = $request->grade_name;
            $marks_grade->gpa = $request->gpa;
            $marks_grade->percent_from = $request->percent_from;
            $marks_grade->percent_upto = $request->percent_upto;
            $marks_grade->description = $request->description;
            $marks_grade->from = $request->grade_from;
            $marks_grade->updated_by=auth()->user()->id;
            $marks_grade->up = $request->grade_upto;
            $result = $marks_grade->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('marks-grade');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try{
            $tables = tableList::getTableList('id', $id);
            if($tables == null ){
                $marks_grade = SmMarksGrade::destroy($id);

                Toastr::success('Operation successful', 'Success');
                return redirect('marks-grade');
            } else{
                $msg = 'This data already used in  : ' . $tables .' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}