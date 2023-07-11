<?php

namespace App\Http\Controllers\api;

use App\SmClass;
use App\SmBookIssue;
use App\ApiBaseMethod;
use App\SmAcademicYear;
use App\SmLibraryMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;

class ApiSmLibraryMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            //$libraryMembers = SmLibraryMember::where('active_status', '=', 1)->get();
            $libraryMembers = SmLibraryMember::where('active_status', '=', 1)->get();
            $roles = InfixRole::where('active_status', 1)->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $classes = SmClass::all();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['libraryMembers'] = $libraryMembers->toArray();
                $data['roles'] = $roles->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.library.members', compact('libraryMembers', 'roles', 'classes'));
        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($request->member_type == "") {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'user_id' => "required",
                    'member_ud_id' => "required|max:120|unique:sm_library_members,member_ud_id",
                ]);
            } elseif ($request->member_type == "2") {

                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'student' => "required",
                    'user_id' => "required",
                    'member_ud_id' => "required|max:120|unique:sm_library_members,member_ud_id",
                ]);
            } else {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'staff' => "required",
                    'user_id' => "required",
                    'member_ud_id' => "required|max:120|unique:sm_library_members,member_ud_id",
                ]);
            }
        } else {
            if ($request->member_type == "") {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'member_ud_id' => "required|max:120|unique:sm_library_members,member_ud_id",
                ]);
            } elseif ($request->member_type == "2") {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'student' => "required",
                    'member_ud_id' => "required|max:120|unique:sm_library_members,member_ud_id",
                ]);
            } else {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'staff' => "required",
                    'member_ud_id' => "required|max:120|unique:sm_library_members,member_ud_id",
                ]);
            }
        }

        $student_staff_id = '';
        if (!empty($request->student)) {
            $student_staff_id = $request->student;
            $isData = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->where('active_status', '=', 1)->first();
            if (!empty($isData)) {
                Toastr::error('This Member is already added in our library', 'Failed');
                return redirect()->back();
            }
        }
        if (!empty($request->staff)) {
            $student_staff_id = $request->staff;
            $isData = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->where('active_status', '=', 1)->first();
            if (!empty($isData)) {
                Toastr::error('This Member is already added in our library', 'Failed');
                return redirect()->back();
            }
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = Auth()->user();

            if ($user) {
                $user_id = $user->id;
            } else {
                $user_id = $request->user_id;
            }

            $isExist_staff_id = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->first();
            //return $isExist_staff_id;
            if (!empty($isExist_staff_id)) {

                $members = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->first();
                ///return $members;
                $members->active_status = 1;
                $results = $members->update();
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                $members = new SmLibraryMember();
                $members->member_type = $request->member_type;
                $members->student_staff_id = $student_staff_id;
                $members->member_ud_id = $request->member_ud_id;
                $members->created_by = $user_id;
                $results = $members->save();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($results) {
                        return ApiBaseMethod::sendResponse(null, 'New Member has been added successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($results) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                }
            }
        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cancelMembership(Request $request, $id)
    {
        try {
            $tables = "";

            try {

                $isExist_member_id = SmBookIssue::select('id', 'issue_status')
                    ->where('member_id', '=', $id)
                    ->where('issue_status', '=', 'I')
                    ->first();
                //return $isExist_member_id;

                if (!empty($isExist_member_id)) {
                    Toastr::error('This member have to return book', 'Failed');
                    return redirect()->back();
                } else {
                    $members = SmLibraryMember::find($id);
                    $members->active_status = 0;
                    $results = $members->update();

                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        if ($results) {
                            return ApiBaseMethod::sendResponse(null, 'Membership has been successfully cancelled');
                        } else {
                            return ApiBaseMethod::sendError('Something went wrong, please try again.');
                        }
                    } else {
                        if ($results) {
                            Toastr::success('Operation successful', 'Success');
                            return redirect()->back();
                        } else {
                            Toastr::error('Operation Failed', 'Failed');
                            return redirect()->back();
                        }
                    }
                }
            } catch (\Illuminate\Database\QueryException$e) {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error('This item already used', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception$e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function library_member_store(Request $request)
    {
        $input = $request->all();
        // return $input;
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($request->member_type == "") {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'created_by' => "required",
                    'member_ud_id' => "required|unique:sm_library_members,member_ud_id",
                ]);
            } elseif ($request->member_type == "2") {

                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'student' => "required",
                    'created_by' => "required",
                    'member_ud_id' => "required|unique:sm_library_members,member_ud_id",
                ]);
            } else {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'staff' => "required",
                    'created_by' => "required",
                    'member_ud_id' => "required|unique:sm_library_members,member_ud_id",
                ]);
            }
        }
        $student_staff_id = '';
        if ($request->student != 0) {
            $student_staff_id = $request->student;
            $isData = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->where('active_status', '=', 1)->first();
            if (!empty($isData)) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('This Member is already added in our library.');
                }
            }
        }
        if ($request->staff != 0) {
            $student_staff_id = $request->staff;
            $isData = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->where('active_status', '=', 1)->first();
            if (!empty($isData)) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('This Member is already added in our library.');
                }
            }
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }

        $user = Auth()->user();

        if ($user) {
            $user_id = $user->id;
        } else {
            $user_id = $request->created_by;
        }

        $isExist_staff_id = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->first();
        if (!empty($isExist_staff_id)) {
            $members = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->first();
            $members->active_status = 1;
            $results = $members->update();
            return ApiBaseMethod::sendResponse(null, 'New Member has been added successfully');
        } else {
            $members = new SmLibraryMember();
            $members->member_type = $request->member_type;
            $members->student_staff_id = $student_staff_id;
            $members->member_ud_id = $request->member_ud_id;
            $members->created_by = $user_id;
            $members->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $results = $members->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($results) {
                    return ApiBaseMethod::sendResponse(null, 'New Member has been added successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            }
        }
    }
    public function saas_library_member_store(Request $request)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($request->member_type == "") {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'created_by' => "required",
                    'member_ud_id' => "required|unique:sm_library_members,member_ud_id",
                    'school_id' => "required",
                ]);
            } elseif ($request->member_type == "2") {

                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'student' => "required",
                    'created_by' => "required",
                    'member_ud_id' => "required|unique:sm_library_members,member_ud_id",
                    'school_id' => "required",
                ]);
            } else {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'staff' => "required",
                    'created_by' => "required",
                    'member_ud_id' => "required|unique:sm_library_members,member_ud_id",
                    'school_id' => "required",
                ]);
            }
        }
        $student_staff_id = '';
        if ($request->student != 0) {
            $student_staff_id = $request->student;
            $isData = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->where('active_status', '=', 1)->where('school_id', '=', $request->school_id)->first();
            if (!empty($isData)) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('This Member is already added in our library.');
                }
            }
        }
        if ($request->staff != 0) {
            $student_staff_id = $request->staff;
            $isData = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->where('active_status', '=', 1)->where('school_id', '=', $request->school_id)->first();
            if (!empty($isData)) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('This Member is already added in our library.');
                }
            }
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }

        $user = Auth()->user();

        if ($user) {
            $created_by = $user->id;
        } else {
            $created_by = $request->created_by;
        }

        $isExist_staff_id = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->where('school_id', '=', $request->school_id)->first();
        if (!empty($isExist_staff_id)) {
            $members = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->where('school_id', '=', $request->school_id)->first();
            $members->active_status = 1;
            $results = $members->update();
            return ApiBaseMethod::sendResponse(null, 'New Member has been added successfully');
        } else {
            $members = new SmLibraryMember();
            $members->member_type = $request->member_type;
            $members->student_staff_id = $student_staff_id;
            $members->member_ud_id = $request->member_ud_id;
            $members->school_id = $request->school_id;
            $members->created_by = $created_by;
            $members->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $results = $members->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($results) {
                    return ApiBaseMethod::sendResponse(null, 'New Member has been added successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            }
        }
    }
}
