<?php

namespace App\Http\Controllers\Admin\Dormitory;


use App\SmRoomType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class SmRoomTypeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $room_types = SmRoomType::get();         
            return view('backEnd.dormitory.room_type', compact('room_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'type' => "required",
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        

        try {
            $room_type = new SmRoomType();
            $room_type->type = $request->type;
            $room_type->description = $request->description;
            $room_type->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $room_type->un_academic_id = getAcademicId();
            }else{
                $room_type->academic_id = getAcademicId();
            }
            $room_type->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $room_type = SmRoomType::find($id);
            $room_types = SmRoomType::where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.dormitory.room_type', compact('room_types', 'room_type'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $room_type = SmRoomType::find($request->id);
            $room_type->type = $request->type;
            $room_type->description = $request->description;
            if(moduleStatusCheck('University')){
                $room_type->un_academic_id = getAcademicId();
            }
            $room_type->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('room-type');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $tables = \App\tableList::getTableList('room_type_id', $id);
            try {
                if ($tables == null) {
                    SmRoomType::destroy($id);

                    Toastr::success('Operation successful', 'Success');
                    return redirect('room-type');
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