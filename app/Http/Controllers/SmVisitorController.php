<?php

namespace App\Http\Controllers;

use App\SmVisitor;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SmVisitorController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {
        try {
            $visitors = SmVisitor::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->orderby('id','DESC')->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($visitors->toArray(), 'Visitors retrieved successfully.');
            }
            return view('backEnd.admin.visitor', compact('visitors'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required|max:120",
            'purpose' => "required|max:250",
            'visitor_id' => "required|max:15",
            'no_of_person' => "required|max:10",
            'date' => "required",
            // 'in_time' => "required",
            // 'out_time' => "required|after:in_time",
            'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
        ]);
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('file');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }

            $fileName = "";
            if ($request->file('file') != "") {
                $file = $request->file('file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/visitor/', $fileName);
                $fileName = 'public/uploads/visitor/' . $fileName;
            }

            $date = strtotime($request->date);

            $newformat = date('Y-m-d', $date);

            $visitor = new SmVisitor();
            $visitor->name = $request->name;
            $visitor->phone = $request->phone;
            $visitor->visitor_id = $request->visitor_id;
            $visitor->no_of_person = $request->no_of_person;
            $visitor->purpose = $request->purpose;
            $visitor->date = $newformat;
            $visitor->in_time = $request->in_time;
            $visitor->out_time = $request->out_time;
            $visitor->file = $fileName;
            $visitor->school_id = Auth::user()->school_id;
            $visitor->academic_id = getAcademicId();
            $result = $visitor->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {

                    return ApiBaseMethod::sendResponse(null, 'Visitor has been created successfully.');
                }
                return ApiBaseMethod::sendError('Something went wrong, please try again.');
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function edit(Request $request, $id)
    {

        try{
             if (checkAdmin()) {
                $visitor = SmVisitor::find($id);
            }else{
                $visitor = SmVisitor::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }

            $visitors = SmVisitor::where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['visitor'] = $visitor->toArray();
                $data['visitors'] = $visitors->toArray();
                return ApiBaseMethod::sendResponse($data, 'Visitor retrieved successfully.');
            }
            return view('backEnd.admin.visitor', compact('visitor', 'visitors'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function update(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => "required|max:120",
            'purpose' => "required|max:250",
            'visitor_id' => "required|max:15",
            'no_of_person' => "required|max:10",
            'date' => "required",
            'in_time' => "required",
            'out_time' => "required",
            'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
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
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('file');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $fileName = "";
            if ($request->file('file') != "") {

                $visitor = SmVisitor::find($request->id);
                if ($visitor->file != "") {
                    $path = url('/') . '/public/uploads/visitor/' . $visitor->file;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $file = $request->file('file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/visitor/', $fileName);
                $fileName = 'public/uploads/visitor/' . $fileName;
            }

            $time = strtotime($request->date);

            $newformat = date('Y-m-d', $time);

             if (checkAdmin()) {
                $visitor = SmVisitor::find($request->id);
            }else{
                $visitor = SmVisitor::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $visitor->name = $request->name;
            $visitor->phone = $request->phone;
            $visitor->visitor_id = $request->visitor_id;
            $visitor->no_of_person = $request->no_of_person;
            $visitor->purpose = $request->purpose;
            $visitor->date = $newformat;
            $visitor->in_time = $request->in_time;
            $visitor->out_time = $request->out_time;
            if ($fileName != "") {
                $visitor->file = $fileName;
            }
            $result = $visitor->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Visitor has been updated successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('visitor');
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
    public function delete(Request $request, $id)
    {

        try{
             if (checkAdmin()) {
                $visitor = SmVisitor::find($id);
            }else{
                $visitor = SmVisitor::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            if ($visitor->file != "") {
                $path = url('/') . '/public/uploads/visitor/' . $visitor->file;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $result = $visitor->delete();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Visitor has been deleted successfully.');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('visitor');
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

    public function download_files($id){

         if (checkAdmin()) {
            $visitor = SmVisitor::find($id);
        }else{
            $visitor = SmVisitor::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
        }
        try {
            if (file_exists($visitor->file)) {
                return Response::download($visitor->file);
            }
        } catch (\Throwable $th) {
            Toastr::error('File not found', 'Failed');
           return redirect()->back();
        }
        
    }
}