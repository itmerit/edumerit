<?php

namespace App\Http\Controllers\Admin\Dormitory;

use App\SmDormitoryList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Dormitory\SmDormitoryRequest;

class SmDormitoryListController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try {
            $dormitory_lists = SmDormitoryList::get();
            return view('backEnd.dormitory.dormitory_list', compact('dormitory_lists'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmDormitoryRequest $request)
    {
        // school wise uquine validation
        try {
            $dormitory_list = new SmDormitoryList();
            $dormitory_list->dormitory_name = $request->dormitory_name;
            $dormitory_list->type = $request->type;
            $dormitory_list->address = $request->address;
            $dormitory_list->intake = $request->intake;
            $dormitory_list->description = $request->description;
            $dormitory_list->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $dormitory_list->un_academic_id = getAcademicId();
            }else{
                $dormitory_list->academic_id = getAcademicId();
            }
            $dormitory_list->save();

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
            $dormitory_list = SmDormitoryList::find($id);
            $dormitory_lists = SmDormitoryList::get();
            return view('backEnd.dormitory.dormitory_list', compact('dormitory_lists', 'dormitory_list'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmDormitoryRequest $request, $id)
    {
        try {
            $dormitory_list = SmDormitoryList::find($request->id);
            $dormitory_list->dormitory_name = $request->dormitory_name;
            $dormitory_list->type = $request->type;
            $dormitory_list->address = $request->address;
            $dormitory_list->intake = $request->intake;
            $dormitory_list->description = $request->description;
            if(moduleStatusCheck('University')){
                $dormitory_list->un_academic_id = getAcademicId();
            }
            $dormitory_list->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('dormitory-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $tables = \App\tableList::getTableList('dormitory_id', $id);
            try {
                if ($tables == null) {
                    SmDormitoryList::destroy($id);
                    Toastr::success('Operation successful', 'Success');
                    return redirect('dormitory-list');
                } else {
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {
                Toastr::error('This item already used', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}