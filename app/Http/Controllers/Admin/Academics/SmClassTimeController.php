<?php

namespace App\Http\Controllers\Admin\Academics;

use App\SmClassTime;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Academics\SmExamTimeRequest;
use App\Http\Requests\Admin\Academics\SmClassTimeRequest;

class SmClassTimeController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        $this->date = generalSetting()->academic_Year->year;
       
    }

    public function index(Request $request)
    {
        try {
            $class_times = SmClassTime::where('type','class')->latest()->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($class_times, null);
            }
            return view('backEnd.academics.class_time', compact('class_times'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function store(SmClassTimeRequest $request)
    {
       

        // if ($validator->fails()) {
        //     if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //         return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
        //     }
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        try {


                $class_time = new SmClassTime();
                $class_time->type = "class";
                $class_time->period = $request->period;
                $class_time->start_time = date('H:i:s', strtotime($request->start_time));
                $class_time->end_time = date('H:i:s', strtotime($request->end_time));
                $class_time->is_break = $request->is_break;
                $class_time->school_id = Auth::user()->school_id;
                $class_time->academic_id = getAcademicId();
                $result = $class_time->save();
                $type = $request->time_type;
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($result) {
                        return ApiBaseMethod::sendResponse($type, 'time has been created successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                }
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
        } catch (\Exception $e) {
            
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function show($id = null)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    public function edit(Request $request, $id)
    {
        try {
            $class_time = SmClassTime::find($id);
            $class_times =SmClassTime::where('type','class')->latest()->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['class_time'] = $class_time->toArray();
                $data['class_times'] = $class_times->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.academics.class_time', compact('class_time', 'class_times'));
        } catch (\Exception $e) {
            
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function update(SmClassTimeRequest $request, $id)
    {
      

        // if ($validator->fails()) {
        //     if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //         return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
        //     }
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        try {
            $class_time = SmClassTime::find($request->id);

            $class_time->type =  $class_time->type = "class";
            $class_time->period = $request->period;
            $class_time->start_time = date('H:i:s', strtotime($request->start_time));
            $class_time->end_time = date('H:i:s', strtotime($request->end_time));
            $class_time->is_break = $request->is_break;
            $result = $class_time->save();

            $type = $request->time_type;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse($type, 'Class Room has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } 
            Toastr::success('Operation successful', 'Success');
            return redirect('class-time');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function destroy(Request $request, $id)
    {
        try {
            $class_id_key = 'class_period_id';
            $exam_id_key = 'exam_period_id';

            $class = \App\tableList::getTableList($class_id_key, $id);
            $exam = \App\tableList::getTableList($exam_id_key, $id);
            $tables = $class . '' . $exam;
            //return $tables;
            try {
                if ($tables == null) {

                    $delete_query = SmClassTime::destroy($id);

                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        if ($delete_query) {
                            return ApiBaseMethod::sendResponse(null, 'Class has been deleted successfully');
                        } else {
                            return ApiBaseMethod::sendError('Something went wrong, please try again.');
                        }
                    } 
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
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function examTime(Request $request){
        try {
            $class_times = SmClassTime::where('type','exam')->latest()->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($class_times, null);
            }
            return view('backEnd.academics.exam_time', compact('class_times'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function examtimeSave(SmExamTimeRequest $request)
    {
      
        // if ($validator->fails()) {
        //     if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //         return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
        //     }
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        try {
            $class_time = new SmClassTime();
            $class_time->type = "exam";
            $class_time->period = $request->period;
            $class_time->start_time = date('H:i:s', strtotime($request->start_time));
            $class_time->end_time = date('H:i:s', strtotime($request->end_time));
            $class_time->school_id = Auth::user()->school_id;
            $class_time->academic_id = getAcademicId();
            $result = $class_time->save();

            $type = $request->time_type;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse($type, 'time has been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } 
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examTimeEdit(Request $request, $id)
    {
        try {
            $class_time = SmClassTime::where('type','exam')->find($id);
            $class_times = SmClassTime::where('type','exam')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['class_time'] = $class_time->toArray();
                $data['class_times'] = $class_times->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.academics.exam_time', compact('class_time', 'class_times'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examTimeUpdate(SmExamTimeRequest $request, $id)
    {


        // if ($validator->fails()) {
        //     if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //         return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
        //     }
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        try {
            // $class_time = SmClassTime::find($request->id);
            $class_time = SmClassTime::where('type','exam')->find($id);
            $class_time->type =  $class_time->type = "exam";
            $class_time->period = $request->period;
            $class_time->start_time = date('H:i:s', strtotime($request->start_time));
            $class_time->end_time = date('H:i:s', strtotime($request->end_time));
            $result = $class_time->save();

            $type = $request->time_type;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse($type, 'Class Room has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } 

            Toastr::success('Operation successful', 'Success');
            return redirect('exam-time');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}