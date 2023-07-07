<?php

namespace App\Http\Controllers\Admin\Hr;
use App\ApiBaseMethod;
use App\SmDesignation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Hr\SmDesignationRequest;

class SmDesignationController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
	}
    public function index(Request $request)
    {
        try {
            $designations = SmDesignation::get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($designations, null);
            }
            return view('backEnd.humanResource.designation', compact('designations'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmDesignationRequest $request)
    {


 

        // if ($validator->fails()) {
        //     if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //         return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
        //     }

        // }

        // school wise uquine validation
      
        try {
            $designation = new SmDesignation();
            $designation->title = $request->title;
            $designation->school_id = Auth::user()->school_id;
            $result = $designation->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Designation has been created successfully');
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            // $designation = SmDesignation::find($id);
            $designation = SmDesignation::find($id);
            $designations = SmDesignation::get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['designation'] = $designation->toArray();
                $data['designations'] = $designations->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.humanResource.designation', compact('designation', 'designations'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmDesignationRequest $request, $id)
    {


        // if ($validator->fails()) {
        //     if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //         return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
        //     }
    
        // }
        // school wise uquine validation

        try {
            // $designation = SmDesignation::find($request->id);
            $designation = SmDesignation::find($request->id);
            $designation->title = $request->title;
            $result = $designation->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Designation has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } 
            Toastr::success('Operation successful', 'Success');
            return redirect('designation');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function destroy(Request $request, $id)
    {

        try {
            $tables = \App\tableList::getTableList('designation_id', $id);
            // return $tables;
            try {
                if ($tables == null) {
                   
                    $designation = SmDesignation::destroy($id);
                    if ($designation) {
                        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                            if ($designation) {
                                return ApiBaseMethod::sendResponse(null, 'Deleted successfully');
                            } else {
                                return ApiBaseMethod::sendError('Something went wrong, please try again');
                            }
                        } 
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
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