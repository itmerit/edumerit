<?php

namespace App\Http\Controllers\Admin\SystemSettings;
use App\SmHoliday;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\GeneralSettings\SmHolidayRequest;

class SmHolidayController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {

        try{
            $holidays = SmHoliday::get();

            return view('backEnd.holidays.holidaysList', compact('holidays'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(SmHolidayRequest $request)
    {

        try{
            $destination =  'public/uploads/holidays/';

            $holidays = new SmHoliday();
            $holidays->holiday_title = $request->holiday_title;
            $holidays->details = $request->details;
            $holidays->from_date = date('Y-m-d', strtotime($request->from_date));
            $holidays->to_date = date('Y-m-d', strtotime($request->to_date));
            $holidays->created_by = Auth::user()->id;
            $holidays->upload_image_file = fileUpload($request->upload_file_name,$destination);
            $holidays->school_id = Auth::user()->school_id;
            $holidays->academic_id = getAcademicId();
            $results = $holidays->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {

        try{
            $editData = SmHoliday::find($id);            
            $holidays = SmHoliday::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

          
            return view('backEnd.holidays.holidaysList', compact('editData', 'holidays'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function update(SmHolidayRequest $request, $id)
    {


        try{

            $destination =  'public/uploads/holidays/';
           $holidays= SmHoliday::find($id);

            $holidays->holiday_title = $request->holiday_title;
            $holidays->details = $request->details;
            $holidays->from_date = date('Y-m-d', strtotime($request->from_date));
            $holidays->to_date = date('Y-m-d', strtotime($request->to_date));
            $holidays->updated_by = auth()->user()->id;
            $holidays->upload_image_file =  fileUpdate($holidays->upload_image_file,$request->upload_file_name,$destination);
            $holidays->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('holiday');

        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function deleteHolidayView(Request $request, $id)
    {
        try{
           
            return view('backEnd.holidays.deleteHolidayView',compact('id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function deleteHoliday(Request $request, $id)
    {
        try{
          
                   
             $holidays = SmHoliday::find($id);
            if ($holidays->upload_image_file != "") {
                unlink($holidays->upload_image_file);
            }
            $holidays->delete();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}