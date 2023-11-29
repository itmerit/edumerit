<?php

namespace App\Http\Controllers;
use App\SmStudent;
use App\tableList;
use App\YearCheck;
use App\SmRoomList;
use App\SmRoomType;
use App\ApiBaseMethod;
use App\SmDormitoryList;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmRoomListController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try{
            $room_lists = SmRoomList::where('active_status', 1)->where('school_id',Auth::user()->school_id)->orderby('id','DESC')->get();
            $room_types = SmRoomType::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
            $dormitory_lists = SmDormitoryList::where('active_status', 1)->where('school_id',Auth::user()->school_id)->orderby('id','DESC')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['room_lists'] = $room_lists->toArray();
                $data['room_types'] = $room_types->toArray();
                $data['dormitory_lists'] = $dormitory_lists->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.dormitory.room_list', compact('room_lists', 'room_types', 'dormitory_lists'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|max:100",
            'dormitory' => "required",
            'room_type' => "required",
            'number_of_bed' => "required|max:2",
            'cost_per_bed' => "required|max:11"
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
            $room_list = new SmRoomList();
            $room_list->name = $request->name;
            $room_list->dormitory_id = $request->dormitory;
            $room_list->room_type_id = $request->room_type;
            $room_list->number_of_bed = $request->number_of_bed;
            $room_list->cost_per_bed = $request->cost_per_bed;
            $room_list->description = $request->description;
            $room_list->school_id = Auth::user()->school_id;
            $room_list->academic_id = getAcademicId();
            $result = $room_list->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Room has been created successfully');
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
            // $room_list = SmRoomList::find($id);
             if (checkAdmin()) {
                $room_list = SmRoomList::find($id);
            }else{
                $room_list = SmRoomList::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $room_lists = SmRoomList::where('school_id',Auth::user()->school_id)->get();
            $room_types = SmRoomType::where('school_id',Auth::user()->school_id)->get();
            $dormitory_lists = SmDormitoryList::where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['room_list'] = $room_list->toArray();
                $data['room_lists'] = $room_lists->toArray();
                $data['room_types'] = $room_types->toArray();
                $data['dormitory_lists'] = $dormitory_lists->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.dormitory.room_list', compact('room_lists', 'room_list', 'room_types', 'dormitory_lists'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|max:100',
            'dormitory' => "required",
            'room_type' => "required",
            'number_of_bed' => "required|max:2",
            'cost_per_bed' => "required|max:11"
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
            // $room_list = SmRoomList::find($request->id);
            if (checkAdmin()) {
                $room_list = SmRoomList::find($request->id);
            }else{
                $room_list = SmRoomList::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $room_list->name = $request->name;
            $room_list->dormitory_id = $request->dormitory;
            $room_list->room_type_id = $request->room_type;
            $room_list->number_of_bed = $request->number_of_bed;
            $room_list->cost_per_bed = $request->cost_per_bed;
            $room_list->description = $request->description;
            $result = $room_list->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Room has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('room-list');
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
            $key_id = 'room_id';

            $tables = SmStudent::where('dormitory_id',$id)->first();

            try {
                if ($tables==null) {
                    if (checkAdmin()) {
                        $delete_query = SmRoomList::destroy($id);
                    }else{
                        $delete_query = SmRoomList::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
                    }
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($delete_query) {
                        return ApiBaseMethod::sendResponse(null, 'Room has been deleted successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($delete_query) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                }
                } else {
                    $msg = 'This data already used in Student Please remove those data first';
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
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}