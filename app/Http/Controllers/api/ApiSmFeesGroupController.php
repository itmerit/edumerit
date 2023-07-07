<?php

namespace App\Http\Controllers\api;

use App\SmClass;
use App\SmStudent;
use App\SmBaseSetup;
use App\SmFeesGroup;
use App\SmFeesAssign;
use App\SmFeesMaster;
use App\ApiBaseMethod;
use App\SmAcademicYear;
use App\SmStudentCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;

class ApiSmFeesGroupController extends Controller
{

    public function fees_group_index(Request $request)
    {

        try {
            $fees_groups = SmFeesGroup::get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($fees_groups, null);
            }

            return view('backEnd.feesCollection.fees_group', compact('fees_groups'));
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_fees_group_index(Request $request, $school_id)
    {

        try {
            $fees_groups = SmFeesGroup::withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', $school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($fees_groups, null);
            }

            return view('backEnd.feesCollection.fees_group', compact('fees_groups'));
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function fees_group_store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|unique:sm_fees_groups|max:200",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $visitor = new SmFeesGroup();
            $visitor->name = $request->name;
            $visitor->description = $request->description;
            $result = $visitor->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Fees Group has been created successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
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
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_fees_group_store(Request $request, $school_id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|unique:sm_fees_groups|max:200",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $visitor = new SmFeesGroup();
            $visitor->name = $request->name;
            $visitor->description = $request->description;
            $visitor->school_id = $school_id;
            $result = $visitor->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Fees Group has been created successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
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
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function fees_group_edit(Request $request, $id)
    {

        try {
            $fees_group = SmFeesGroup::find($id);
            $fees_groups = SmFeesGroup::get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['fees_group'] = $fees_group->toArray();
                $data['fees_groups'] = $fees_groups->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.feesCollection.fees_group', compact('fees_group', 'fees_groups'));
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_fees_group_edit(Request $request, $school_id, $id)
    {

        try {
            $fees_group = SmFeesGroup::where('school_id', $school_id)->find($id);
            $fees_groups = SmFeesGroup::where('school_id', $school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['fees_group'] = $fees_group->toArray();
                $data['fees_groups'] = $fees_groups->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.feesCollection.fees_group', compact('fees_group', 'fees_groups'));
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function fees_group_update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|max:200|unique:sm_fees_groups,name," . $request->id,

        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $visitor = SmFeesGroup::find($request->id);
            $visitor->name = $request->name;
            $visitor->description = $request->description;
            $result = $visitor->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Fees Group has been updated successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('fees-group');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_fees_group_update(Request $request, $school_id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|max:200",

        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $visitor = SmFeesGroup::where('school_id', $request->school_id)->find($request->id);
            $visitor->name = $request->name;
            $visitor->description = $request->description;
            $visitor->school_id = $school_id;
            $result = $visitor->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Fees Group has been updated successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('fees-group');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function fees_group_delete(Request $request)
    {

        try {
            $fees_group = SmFeesGroup::destroy($request->id);

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($fees_group) {
                    return ApiBaseMethod::sendResponse(null, 'Fees Group has been deleted successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($fees_group) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('fees-group');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect('fees-group');
                }
            }
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
    public function saas_fees_group_delete(Request $request, $school_id)
    {

        try {
            $fees_group = SmFeesGroup::where('school_id', $school_id)->where('id', $request->id)->delete();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($fees_group) {
                    return ApiBaseMethod::sendResponse(null, 'Fees Group has been deleted successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($fees_group) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('fees-group');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect('fees-group');
                }
            }
        } catch (\Exception$e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
}
