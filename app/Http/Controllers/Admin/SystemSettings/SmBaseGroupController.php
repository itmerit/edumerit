<?php

namespace App\Http\Controllers\Admin\SystemSettings;

use App\tableList;
use App\SmBaseGroup;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class SmBaseGroupController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request){
        try{
            $base_groups = SmBaseGroup::where('active_status', '=', 1)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($base_groups, null);
            }
            return view('backEnd.systemSettings.baseSetup.base_group', compact('base_groups'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
    		'name' => "required"
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
            $base_group = new SmBaseGroup();
            $base_group->name = $request->name;
            $base_group->school_id = Auth::user()->school_id;
            $result = $base_group->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Base Group has been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                    // return redirect()->back()->with('message-success', 'Base Group has been created successfully');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                    // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
                }
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit(Request $request,$id){
        try{
            // $base_group = SmBaseGroup::find($id);
             if (checkAdmin()) {
                $base_group = SmBaseGroup::find($id);
            }else{
                $base_group = SmBaseGroup::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $base_groups = SmBaseGroup::where('active_status', '=', 1)->orderBy('id', 'desc')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['base_group'] = $base_group;
                $data['base_groups'] = $base_groups->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.systemSettings.baseSetup.base_group', compact('base_group', 'base_groups'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
    		'name' => "required"
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
            // $base_group = SmBaseGroup::find($request->id);
             if (checkAdmin()) {
                $base_group = SmBaseGroup::find($request->id);
            }else{
                $base_group = SmBaseGroup::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $base_group->name = $request->name;
            $result = $base_group->save();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Base Group has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                    // return redirect()->back()->with('message-success', 'Base Group has been updated successfully');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                    // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
                }
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete(Request $request,$id){

        try {
            if(ApiBaseMethod::checkUrl($request->fullUrl())) {
                // $result = $delete_query = SmBaseGroup::destroy($id);
                 if (checkAdmin()) {
                    $result = SmBaseGroup::destroy($id);
                }else{
                    $result = SmBaseGroup::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                }
                if($result){
                    return ApiBaseMethod::sendResponse(null, 'Base group has been deleted successfully');
                }else{
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            }else{
                $result = $delete_query = SmBaseGroup::destroy($request->id);
                if($delete_query){
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }else{
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }

        } catch (\Illuminate\Database\QueryException $e) {
            Toastr::error('This item already used', 'Failed');
            return redirect()->back();
          }

    }
}
