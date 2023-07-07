<?php

namespace App\Http\Controllers\api;

use Validator;
use App\SmStaff;
use App\SmVehicle;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SaasVehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function index(Request $request)
    {
        try {
            $input =$request->all();
            $validator = Validator::make($input, [
                'school_id' => "required|integer",
            ]);
    
            if ($validator->fails()) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
                }
    
            }
            $drivers = SmStaff::where([['active_status', 1], ['role_id', 9],['school_id',$request->school_id]])->get();
            $assign_vehicles = SmVehicle::where('school_id',$request->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['drivers'] = $drivers->toArray();
                $data['assign_vehicles'] = $assign_vehicles->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.transport.vehicle', compact('assign_vehicles', 'drivers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'vehicle_number' => "required|unique:sm_vehicles,vehicle_no|max:200",
            'vehicle_model' => "required|max:200",
            'year_made' => "sometimes|nullable|max:10",
            'driver_id' => "required",
            'school_id' => "required",
            'created_by' => "required",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }

        }
        try {
            $assign_vehicle = new SmVehicle();
            $assign_vehicle->vehicle_no = $request->vehicle_number;
            $assign_vehicle->vehicle_model = $request->vehicle_model;
            if ($request->year_made) {
                $assign_vehicle->made_year = $request->year_made;
            }
            $assign_vehicle->driver_id = $request->driver_id;
            $assign_vehicle->created_by = $request->created_by;
            // $assign_vehicle->driver_contact = $request->driver_contact;
            $assign_vehicle->note = $request->note;
            $assign_vehicle->school_id = $request->school_id;
            $result = $assign_vehicle->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Vehicle has been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function show(Request $request, $id)
    {
        try {
            //$drivers = SmStaff::where('active_status', 1)->get();
            $assign_vehicle = SmVehicle::find($id);
            $drivers = SmStaff::where([['active_status', 1], ['role_id', 9],['school_id',$request->school_id]])->get();
            $assign_vehicles = SmVehicle::where('school_id',$request->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['route'] = $drivers->toArray();
                $data['routes'] = $assign_vehicle;
                $data['routes'] = $assign_vehicles->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.transport.vehicle', compact('assign_vehicle', 'assign_vehicles', 'drivers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }


    public function update(Request $request, $id)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'vehicle_number' => 'required|max:200|unique:sm_vehicles,vehicle_no,' . $id,
                'vehicle_model' => "required|max:200",
                'year_made' => "sometimes|nullable|max:10",
                'id' => 'required',
                'updated_by' => 'required',
            ]);
        } 
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }

        }

        try {
            $assign_vehicle = SmVehicle::find($request->id);
            $assign_vehicle->vehicle_no = $request->vehicle_number;
            $assign_vehicle->vehicle_model = $request->vehicle_model;
            $assign_vehicle->made_year = $request->year_made;
            $assign_vehicle->driver_id = $request->driver_id;
            $assign_vehicle->updated_by = $request->updated_by;
            // $assign_vehicle->driver_contact = $request->driver_contact;
            $assign_vehicle->note = $request->note;
            $result = $assign_vehicle->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Vehicle has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
            } 
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function destroy(Request $request, $id)
    {

        $tables = \App\tableList::getTableList('vehicle_id',$id);
        try {
            $vehicle = SmVehicle::destroy($id);
            if ($vehicle) {

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($vehicle) {
                        return ApiBaseMethod::sendResponse(null, 'Vehicle has been deleted successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
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
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }
}
