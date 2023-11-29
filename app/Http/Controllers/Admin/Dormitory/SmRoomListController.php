<?php

namespace App\Http\Controllers\Admin\Dormitory;
use App\SmStudent;
use App\SmRoomList;
use App\SmRoomType;
use App\SmDormitoryList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Dormitory\SmDormitoryRoomRequest;

class SmRoomListController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try{
            $room_lists = SmRoomList::with('dormitory','roomType')->get();
            $room_types = SmRoomType::get();
            $dormitory_lists = SmDormitoryList::orderby('id','DESC')->get();
            return view('backEnd.dormitory.room_list', compact('room_lists', 'room_types', 'dormitory_lists'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(SmDormitoryRoomRequest $request)
    {
        try{
            $room_list = new SmRoomList();
            $room_list->name = $request->name;
            $room_list->dormitory_id = $request->dormitory;
            $room_list->room_type_id = $request->room_type;
            $room_list->number_of_bed = $request->number_of_bed;
            $room_list->cost_per_bed = $request->cost_per_bed;
            $room_list->description = $request->description;
            $room_list->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $room_list->un_academic_id = getAcademicId();
            }else{
                $room_list->academic_id = getAcademicId();
            }
            $room_list->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function show(Request $request, $id)
    {
        try{
            $room_list = SmRoomList::find($id);
            $room_lists = SmRoomList::with('dormitory','roomType')->get();
            $room_types = SmRoomType::get();
            $dormitory_lists = SmDormitoryList::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.dormitory.room_list', compact('room_lists', 'room_list', 'room_types', 'dormitory_lists'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(SmDormitoryRoomRequest $request, $id)
    {
        try{
            $room_list = SmRoomList::find($request->id);           
            $room_list->name = $request->name;
            $room_list->dormitory_id = $request->dormitory;
            $room_list->room_type_id = $request->room_type;
            $room_list->number_of_bed = $request->number_of_bed;
            $room_list->cost_per_bed = $request->cost_per_bed;
            $room_list->description = $request->description;
            if(moduleStatusCheck('University')){
                $room_list->un_academic_id = getAcademicId();
            }
            $room_list->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('room-list');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try{
            $key_id = 'room_id';
            $tables = SmStudent::where('dormitory_id',$id)->first();
            try {
                if ($tables==null) {
                    SmRoomList::destroy($id);

                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
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