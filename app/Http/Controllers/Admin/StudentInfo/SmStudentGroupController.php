<?php

namespace App\Http\Controllers\Admin\StudentInfo;

use App\ApiBaseMethod;
use App\SmStudentGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\StudentInfo\StudentGroupRequest;

class SmStudentGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }
    public function index(Request $request)
    {

        try {
            $student_groups = SmStudentGroup::withCount('students')->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($student_groups, null);
            }

            return view('backEnd.studentInformation.student_group', compact('student_groups'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(StudentGroupRequest $request)
    {
        try {
            $student_group = new SmStudentGroup();
            $student_group->group = $request->group;
            $student_group->school_id = Auth::user()->school_id;
            $student_group->created_by = auth()->user()->id;
            $student_group->academic_id = getAcademicId();
            $result = $student_group->save();

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
            $student_group = SmStudentGroup::find($id);
            $student_groups = SmStudentGroup::withCount('students')->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.studentInformation.student_group', compact('student_groups', 'student_group'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(StudentGroupRequest $request)
    {
        try {
            $student_group = SmStudentGroup::find($request->id);
            $student_group->group = $request->group;
            $student_group->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('student-group');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete(Request $request, $id)
    {

        try {
            $tables = \App\tableList::getTableList('student_group_id', $id);
            try {
                if ($tables == null) {
                    SmStudentGroup::destroy($id);
                                        
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {

                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
