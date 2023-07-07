<?php

namespace App\Http\Controllers\Admin\Academics;

use App\SmSection;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmClassSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Academics\SectionRequest;
use Modules\University\Entities\UnAcademicYear;

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
            $sections = SmSection::query();
            if(moduleStatusCheck('University')){
            $data = $sections->where('un_academic_id',getAcademicId());
            }else{
                $data = $sections->where('academic_id',getAcademicId());
            }
            $sections = $data->where('school_id',auth()->user()->school_id)->get();

            $unAcademics = null;
            if (moduleStatusCheck('University')) {
                $unAcademics = UnAcademicYear::where('school_id', auth()->user()->school_id)->get()
                ->pluck('name', 'id')
                ->prepend(__('university::un.select_academic'), ' *')
                ->toArray();
            }
            return view('backEnd.academics.section', compact('sections', 'unAcademics'));
        } catch (\Exception $e) {
          
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(SectionRequest $request)
    { 
        $academic_year=academicYears();
        if ($academic_year==null) {
            Toastr::warning('Create academic year first', 'Warning');
            return redirect()->back();
        }
        try {
            $section = new SmSection();
            $section->section_name = $request->name;
            $section->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
            $section->school_id = Auth::user()->school_id;
            $section->created_at=auth()->user()->id;
            $section->academic_id = !moduleStatusCheck('University') ? getAcademicId() : null;
            if (moduleStatusCheck('University')) {
                $section->un_academic_id = getAcademicId();
            }
            $result = $section->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Section has been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            }
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
            $section = SmSection::where('id',$id)->where('school_id',auth()->user()->school_id)->first();
            if(is_null($section)){
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
            $sections = SmSection::query();
            if(moduleStatusCheck('University')){
            $data = $sections->where('un_academic_id',getAcademicId());
            }else{
                $data = $sections->whereNull('un_academic_id')->where('academic_id',getAcademicId());
            }
            $sections = $data->where('school_id',auth()->user()->school_id)->get();
            $unAcademics = null;
            if (moduleStatusCheck('University')) {
                $unAcademics = UnAcademicYear::where('school_id', auth()->user()->school_id)->get()
                ->pluck('name', 'id')
                ->prepend(__('university::un.select_academic'), ' *')
                ->toArray();
            }
       
            return view('backEnd.academics.section', compact('section', 'sections', 'unAcademics'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(SectionRequest $request)
    {
        try {
          
            $section = SmSection::find($request->id);
            $section->section_name = $request->name;
            $result = $section->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Section has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } 
            Toastr::success('Operation successful', 'Success');
            return redirect('section');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete(Request $request, $id)
    {
        try {
            $tables = SmClassSection::where('section_id', $id)->first();
                if ($tables == null) {
                          SmSection::destroy($request->id);
                          Toastr::success('Operation successful', 'Success');
                          return redirect('section');
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