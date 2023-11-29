<?php

namespace App\Http\Controllers\Admin\Transport;

use App\SmStaff;
use App\SmVehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Transport\SmVehicleRequest;

class SmVehicleController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try {
            $drivers = SmStaff::whereRole(9)->get();
            $assign_vehicles = SmVehicle::status()->get();
            return view('backEnd.transport.vehicle', compact('assign_vehicles', 'drivers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmVehicleRequest $request)
    {
        try {
            $assign_vehicle = new SmVehicle();
            $assign_vehicle->vehicle_no = $request->vehicle_number;
            $assign_vehicle->vehicle_model = $request->vehicle_model;    
            $assign_vehicle->made_year = $request->year_made;   
            $assign_vehicle->driver_id = $request->driver_id;
            $assign_vehicle->note = $request->note;
            $assign_vehicle->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $assign_vehicle->un_academic_id = getAcademicId();
            }else{
                $assign_vehicle->academic_id = getAcademicId();
            }
            $assign_vehicle->save();

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
            $drivers = SmStaff::whereRole(9)->get();        
            $assign_vehicle = SmVehicle::find($id);
            $assign_vehicles = SmVehicle::where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.transport.vehicle', compact('assign_vehicle', 'assign_vehicles', 'drivers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function update(SmVehicleRequest $request, $id)
    {
        try {
            $assign_vehicle = SmVehicle::find($request->id);
            $assign_vehicle->vehicle_no = $request->vehicle_number;
            $assign_vehicle->vehicle_model = $request->vehicle_model;
            $assign_vehicle->made_year = $request->year_made;
            $assign_vehicle->driver_id = $request->driver_id;
            $assign_vehicle->note = $request->note;
            if(moduleStatusCheck('University')){
                $assign_vehicle->un_academic_id = getAcademicId();
            }
            $assign_vehicle->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('vehicle');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        $tables = \App\tableList::getTableList('vehicle_id', $id);
        try {
            if ($tables == null) {
                SmVehicle::destroy($id);

                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
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
    }
}