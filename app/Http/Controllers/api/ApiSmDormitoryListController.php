<?php

namespace App\Http\Controllers\api;

use List;
use Validator;
use App\SmClass;
use App\SmStudent;
use App\ApiBaseMethod;
use App\SmAcademicYear;
use App\SmDormitoryList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;

class ApiSmDormitoryListController extends Controller
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

            
        try{
            $dormitory_lists = SmDormitoryList::all();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($dormitory_lists, null);
            }
            return view('backEnd.dormitory.dormitory_list', compact('dormitory_lists'));
        }catch (\Exception $e) {
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
        $validator = Validator::make($input, [
            'dormitory_name' => "required|unique:sm_dormitory_lists,dormitory_name",
            'type' => "required",
            'intake' => "required"
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try{
            $dormitory_list = new SmDormitoryList();
            $dormitory_list->dormitory_name = $request->dormitory_name;
            $dormitory_list->type = $request->type;
            $dormitory_list->address = $request->address;
            $dormitory_list->intake = $request->intake;
            $dormitory_list->description = $request->description;
            $result = $dormitory_list->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Dormitory has been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($result) {
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try{
            $dormitory_list = SmDormitoryList::find($id);
            $dormitory_lists = SmDormitoryList::all();
    
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['dormitory_list'] = $dormitory_list;
                $data['dormitory_lists'] = $dormitory_lists->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.dormitory.dormitory_list', compact('dormitory_lists', 'dormitory_list'));   
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back(); 
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return 'dsfsd';
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
        $input = $request->all();
        $validator = Validator::make($input, [
            'dormitory_name' => 'required|unique:sm_dormitory_lists,dormitory_name,' . $id,
            'type' => "required",
            'intake' => "required"
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try{
            $dormitory_list = SmDormitoryList::find($request->id);
            $dormitory_list->dormitory_name = $request->dormitory_name;
            $dormitory_list->type = $request->type;
            $dormitory_list->address = $request->address;
            $dormitory_list->intake = $request->intake;
            $dormitory_list->description = $request->description;
            $result = $dormitory_list->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Dormitory has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('dormitory-list');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try{
            $tables = \App\tableList::getTableList('dormitory_id',$id);
            try {
                $dormitory_list = SmDormitoryList::destroy($id);
                if ($dormitory_list) {
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        if ($dormitory_list) {
                            return ApiBaseMethod::sendResponse(null, 'Dormitory has been deleted successfully');
                        } else {
                            return ApiBaseMethod::sendError('Something went wrong, please try again');
                        }
                    } else {
                        if ($dormitory_list) {
                            Toastr::success('Operation successful', 'Success');
                            return redirect('dormitory-list');
                        } else {
                            Toastr::error('Operation Failed', 'Failed');
                            return redirect()->back();
                        }
                    }
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error('This item already used', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function saas_studentDormitoryReportSearch(Request $request, $school_id)
    {

        try {
            $student_ids = studentRecords($request, null, $school_id)->pluck('student_id')->unique();
            $students = SmStudent::query();
            $students->where('active_status', 1)->where('school_id', $school_id);

            if ($request->dormitory != "") {
                $students->where('dormitory_id', $request->dormitory)->where('school_id', $school_id);
            } else {
                $students->where('dormitory_id', '!=', '')->where('school_id', $school_id);
            }
            $students = $students->whereIn('id', $student_ids)->get();

            $classes = SmClass::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('active_status', 1)->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->where('school_id', $school_id)->get();
            $dormitories = SmDormitoryList::where('active_status', 1)->where('school_id', $school_id)->get();

            $class_id = $request->class;
            $dormitory_id = $request->dormitory;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['dormitories'] = $dormitories->toArray();
                $data['students'] = $students->toArray();
                $data['class_id'] = $class_id;
                $data['dormitory_id'] = $dormitory_id;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.dormitory.student_dormitory_report', compact('classes', 'dormitories', 'students', 'class_id', 'dormitory_id'));
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
}
