<?php

namespace App\Http\Controllers\Admin\Transport;

use App\SmRoute;
use App\SmVehicle;
use App\SmAssignVehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Transport\SmAssignVehicleRequest;

class SmAssignVehicleController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try {
            $routes = SmRoute::get();
            $assign_vehicles = SmAssignVehicle::with('route','vehicle')->where('school_id', Auth::user()->school_id)->get();
            $vehicles = SmVehicle::select('id', 'vehicle_no')->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.transport.assign_vehicle', compact('routes', 'assign_vehicles', 'vehicles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmAssignVehicleRequest $request)
    {
        try {
            $assign_vehicle = new SmAssignVehicle();
            $assign_vehicle->route_id = $request->route;
            $vehicles = '';
            $i = 0;
            foreach ($request->vehicles as $vehicle) {
                $i++;
                if ($i == 1) {
                    $vehicles .=  $vehicle;
                } else {
                    $vehicles .=  ',';
                    $vehicles .=  $vehicle;
                }
            }
            $assign_vehicle->vehicle_id = $vehicles;
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

    public function edit(Request $request, $id)
    {
        try {
            $routes = SmRoute::get();
            $assign_vehicles = SmAssignVehicle::with('route','vehicle')->where('school_id', Auth::user()->school_id)->get();
            $assign_vehicle  = SmAssignVehicle::find($id);
            $vehiclesIds     = explode(',', $assign_vehicle->vehicle_id);
            $vehicles        = SmVehicle::select('id', 'vehicle_no')->get();
            return view('backEnd.transport.assign_vehicle', compact('routes', 'assign_vehicles', 'assign_vehicle', 'vehicles', 'vehiclesIds'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmAssignVehicleRequest $request, $id)
    {
        try {
            $assign_vehicle = SmAssignVehicle::find($id);
            $assign_vehicle->route_id = $request->route;
            $vehicles = '';
            $i = 0;
            foreach ($request->vehicles as $vehicle) {
                $i++;
                if ($i == 1) {
                    $vehicles .=  $vehicle;
                } else {
                    $vehicles .=  ',';
                    $vehicles .=  $vehicle;
                }
            }
            $assign_vehicle->vehicle_id = $vehicles;
            if(moduleStatusCheck('University')){
                $assign_vehicle->un_academic_id = getAcademicId();
            }else{
                $assign_vehicle->academic_id = getAcademicId();
            }
            $assign_vehicle->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('assign-vehicle');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try {
            SmAssignVehicle::where('id', $request->id)->delete();
            
            Toastr::success('Operation successful', 'Success');
            return redirect('assign-vehicle');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}