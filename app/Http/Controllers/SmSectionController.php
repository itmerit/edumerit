<?php

namespace App\Http\Controllers;

use App\SmSection;
use App\YearCheck;
use App\ApiBaseMethod;
use App\BranchSection;
use App\SmAcademicYear;
use App\SmClassSection;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmSectionController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {
        

        try {
      
       $academic_year=SmAcademicYear::where('school_id',Auth::user()->school_id)->where('id', getAcademicId())->first();
            
            $sections = SmSection::where('active_status', '=', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
             //return $sections;
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($sections, null);
            }
            return view('backEnd.academics.section', compact('sections'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
       
        $academic_year=SmAcademicYear::where('school_id',Auth::user()->school_id)->where('id', getAcademicId())->first();
        if ($academic_year==null) {
            Toastr::warning('Create academic year first', 'Warning');
            return redirect()->back();
        }

        $input = $request->all();
        $validator = Validator::make($input, [                                                 
            'name' => "required|max:200"
        ]);
        $is_duplicate = SmSection::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('section_name', $request->name)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate section name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }



        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

    if( moduleStatusCheck('MultiBranch')){
        if(Auth::user()->is_administrator=='yes'){
                                $branchs = $request->branch;

                    if ($branchs != '') {
                        foreach ($branchs as $branch) {
                            $smbranchSection = new BranchSection();
                            $smbranchSection->section_id = $branch;
                             $smbranchSection->branch_id = $branch;
                            $smbranchSection->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            $smbranchSection->school_id = Auth::user()->school_id;

                             //check MultiBranch module and superadmin
                // if( moduleStatusCheck('MultiBranch')){
                //     if(Auth::user()->is_administrator=='yes'){
                //         $user->branch_id = $request->branch_id;
                //     }else{
                //         $user->branch_id = Auth::user()->branch_id;
                //     }
                
                //  }
                            $smbranchSection->academic_id = getAcademicId();
                            $smbranchSection->save();
                        }
                    }
        }
    }
        try {
            $section = new SmSection();
            $section->section_name = $request->name;
            $section->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
            $section->school_id = Auth::user()->school_id;
            $section->academic_id = getAcademicId();
            $result = $section->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Section has been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit(Request $request, $id)
    {

        try {
            // $section = SmSection::find($id);
             if (checkAdmin()) {
                $section = SmSection::find($id);
            }else{
                $section = SmSection::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $sections = SmSection::where('active_status', '=', 1)->orderBy('id', 'desc')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['section'] = $section->toArray();
                $data['sections'] = $sections->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.academics.section', compact('section', 'sections'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|max:200"
        ]);

        $is_duplicate = SmSection::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->where('section_name', $request->name)->where('id','!=', $request->id)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate section name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
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
            // $section = SmSection::find($request->id);
            if (checkAdmin()) {
                $section = SmSection::find($request->id);
            }else{
                $section = SmSection::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $section->section_name = $request->name;
            $result = $section->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Section has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');

                    return redirect('section');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete(Request $request, $id)
    {
        try {
            $tables = SmClassSection::where('section_id',$id)->first();
                if ($tables == null) {
                      if (checkAdmin()) {
                        $delete_query = SmSection::destroy($request->id);
                    }else{
                        $delete_query = SmSection::where('id',$request->id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                        if ($delete_query) {
                            Toastr::success('Operation successful', 'Success');
                            return redirect('section');
                        } else {
                            Toastr::error('Operation Failed', 'Failed');
                            return redirect()->back();
                        }
                } else {
                    $msg = 'This section already assigned with class .';
                    Toastr::warning($msg, 'Warning');
                    return redirect()->back();
                }

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}