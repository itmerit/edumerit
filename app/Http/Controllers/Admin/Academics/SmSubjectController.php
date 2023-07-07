<?php

namespace App\Http\Controllers\Admin\Academics;

use App\SmSubject;
use App\tableList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Admin\Academics\SmSubjectRequest;

class SmSubjectController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {

        try {
            $subjects = SmSubject::orderBy('id', 'DESC')->get();

            return view('backEnd.academics.subject', compact('subjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(SmSubjectRequest $request)
    {
        try {
            $subject = new SmSubject();
            $subject->subject_name = $request->subject_name;
            $subject->subject_type = $request->subject_type;
            $subject->subject_code = $request->subject_code;
            if (@generalSetting()->result_type == 'mark'){
                $subject->pass_mark = $request->pass_mark;
            }
            $subject->created_by   = auth()->user()->id;
            $subject->school_id    = auth()->user()->school_id;
            $subject->academic_id  = getAcademicId();
            $result = $subject->save();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit(Request $request, $id)
    {
        try {
            $subject = SmSubject::find($id);
            $subjects = SmSubject::orderBy('id', 'DESC')->get();
            return view('backEnd.academics.subject', compact('subject', 'subjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(SmSubjectRequest $request)
    {
        try {
            $subject = SmSubject::find($request->id);
            $subject->subject_name = $request->subject_name;
            $subject->subject_type = $request->subject_type;
            $subject->subject_code = $request->subject_code;
            if (@generalSetting()->result_type == 'mark'){
                $subject->pass_mark = $request->pass_mark;
            }
            $subject->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->route('subject');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete(Request $request, $id)
    {
        try {
            $tables = tableList::getTableList('subject_id', $id);
            try {
                if ($tables == null) {
                    // $delete_query = $section = SmSubject::destroy($id);
                         SmSubject::destroy($id);
                         Toastr::success('Operation successful', 'Success');
                         return redirect('subject');
                } else {
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {

                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}