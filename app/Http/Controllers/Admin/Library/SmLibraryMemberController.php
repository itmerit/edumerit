<?php

namespace App\Http\Controllers\Admin\Library;
use App\Role;
use App\SmClass;
use App\SmStudent;
use App\YearCheck;
use App\SmBookIssue;
use App\ApiBaseMethod;
use App\SmLibraryMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;

class SmLibraryMemberController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {

        try{
            $libraryMembers = SmLibraryMember::with('roles','studentDetails','staffDetails','parentsDetails','memberTypes')->where('active_status', '=', 1)
            ->where('school_id',Auth::user()->school_id)
            ->orderby('id','ASC')
            ->get();

            $roles = InfixRole::where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();

            $classes = SmClass::get();

            return view('backEnd.library.members', compact('libraryMembers', 'roles', 'classes'));

        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'member_type' => "required",
            'student' => "required_if:member_type,2",
            'member_ud_id' => "required|max:120|unique:sm_library_members,member_ud_id"
        ],
        [
            'member_ud_id.required' => 'The Member id is required',
            'member_ud_id.unique' => 'The Member id must be an unique value'
        ]
    );
            if ($request->member_type == "") {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'member_ud_id' => "required|max:120|unique:sm_library_members,member_ud_id"
                ],
                [
                    'member_ud_id.required' => 'The Member id is required',
                    'member_ud_id.unique' => 'The Member id must be an unique value'
                ]);
            } elseif ($request->member_type == "2") {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'student' => "required",
                    'member_ud_id' => "required|max:120|unique:sm_library_members,member_ud_id"
                ],
                [
                    'member_ud_id.required' => 'The Member id is required',
                    'member_ud_id.unique' => 'The Member id must be an unique value'
                ]);
            } else {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'staff' => "required",
                    'member_ud_id' => "required|max:120|unique:sm_library_members,member_ud_id"
                ],
                [
                    'member_ud_id.required' => 'The Member id is required',
                    'member_ud_id.unique' => 'The Member id must be an unique value'
                ]);
            }

        $student_staff_id = '';
        if (!empty($request->student)) {
            $student = SmStudent::where('user_id', $request->student)->first();
            $student_staff_id = $student->user_id;
            
            $isData = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->first();
            if (!empty($isData)) {
                Toastr::error('This Member is already added in our library', 'Failed');
                return redirect()->back();
            }
        }
        if (!empty($request->staff)) {
            $student_staff_id = $request->staff;
            $isData = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->first();
            if (!empty($isData)) {
                Toastr::error('This Member is already added in our library', 'Failed');
                return redirect()->back();
            }
        }


        if ($validator->fails()) {   
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try{
            $user = Auth()->user();
            if ($user) {
                $user_id = $user->id;
            } else {
                $user_id = $request->user_id;
            }
            $isExitMember = SmLibraryMember::where('student_staff_id', $student_staff_id)->withoutGlobalScopes()->status()->first();
            if (!empty($isExitMember)) {
                $members = $isExitMember;
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
                $members->school_id = auth()->user()->school_id;
                if(moduleStatusCheck('University')){
                    $common = App::make(UnCommonRepositoryInterface::class);
                    $common->storeUniversityData($members, $request);
                    if($request->member_type != 2){
                        $members->un_academic_id = getAcademicId();
                    }
                }else{
                    $members->academic_id = getAcademicId();
                }

                $results = $members->save();

                if ($results) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    

    public function cancelMembership(Request $request, $id)
    {
        try{
            $tables = "";
            try {
                $isExist_member_id = SmBookIssue::select('id', 'issue_status')
                    ->where('member_id', '=', $id)
                    ->where('issue_status', '=', 'I')
                    ->first();

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
            } catch (\Illuminate\Database\QueryException $e) {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error('This item already used', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}