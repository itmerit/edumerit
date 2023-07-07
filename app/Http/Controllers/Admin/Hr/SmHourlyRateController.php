<?php

namespace App\Http\Controllers\Admin\Hr;
use App\SmHourlyRate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SmHourlyRateController extends Controller
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
    public function index()
    {

        try{
            $hourly_rates = SmHourlyRate::all();
            return view('backEnd.humanResource.hourly_rate', compact('hourly_rates'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'grade' => "required",
            'rate' => "required"
        ]);


        try{
            $hourly_rate = new SmHourlyRate();
            $hourly_rate->grade = $request->grade;
            $hourly_rate->rate = $request->rate;
            $hourly_rate->academic_id = getAcademicId();
            $result = $hourly_rate->save();
            if($result){
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
                // return redirect()->back()->with('message-success', 'Rate has been created successfully');
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
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
    public function show($id)
    {

        try{
            $hourly_rate = SmHourlyRate::find($id);
            $hourly_rates = SmHourlyRate::all();
            return view('backEnd.humanResource.hourly_rate', compact('hourly_rates', 'hourly_rate'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'grade' => "required",
            'rate' => "required"
        ]);
        try{
            $hourly_rate = SmHourlyRate::find($request->id);
            $hourly_rate->grade = $request->grade;
            $hourly_rate->rate = $request->rate;
            $result = $hourly_rate->save();
            if($result){
                Toastr::success('Operation successful', 'Success');
                return redirect('hourly-rate');
                // return redirect('hourly-rate')->with('message-success', 'Rate has been updated successfully');
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
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
    public function destroy($id)
    {

        try{
            $hourly_rate = SmHourlyRate::destroy($id);
            if($hourly_rate){
                Toastr::success('Operation successful', 'Success');
                return redirect('hourly-rate');
                // return redirect('hourly-rate')->with('message-success-delete', 'Rate has been deleted successfully');
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}