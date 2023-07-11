<?php

namespace App\Http\Controllers\Admin\AdminSection;

use DataTables;
use App\SmVisitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Admin\AdminSection\SmVisitorRequest;

class SmVisitorController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try {
            return view('backEnd.admin.visitor');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmVisitorRequest $request)
    {
        try {
            $destination = 'public/uploads/visitor/';
            $fileName=fileUpload($request->file,$destination); 
            $visitor = new SmVisitor();
            $visitor->name = $request->name;
            $visitor->phone = $request->phone;
            $visitor->visitor_id = $request->visitor_id;
            $visitor->no_of_person = $request->no_of_person;
            $visitor->purpose = $request->purpose;
            $visitor->date = date('Y-m-d',strtotime($request->date));
            $visitor->in_time = $request->in_time;
            $visitor->out_time = $request->out_time;
            $visitor->file = $fileName;
            $visitor->created_by=auth()->user()->id;
            $visitor->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $visitor->un_academic_id = getAcademicId();
            }else{
                $visitor->academic_id = getAcademicId();
            }
            $visitor->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {
        try{
            $visitor = SmVisitor::find($id);
            $visitors = SmVisitor::orderby('id','DESC')->get();
            return view('backEnd.admin.visitor', compact('visitor', 'visitors'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmVisitorRequest $request)
    {
        try{
            $destination = 'public/uploads/visitor/';
            $visitor = SmVisitor::find($request->id);
            $visitor->name = $request->name;
            $visitor->phone = $request->phone;
            $visitor->visitor_id = $request->visitor_id;
            $visitor->no_of_person = $request->no_of_person;
            $visitor->purpose = $request->purpose;
            $visitor->date = date('Y-m-d',strtotime($request->date));
            $visitor->in_time = $request->in_time;
            $visitor->out_time = $request->out_time;          
            $visitor->file = fileUpdate($visitor->file,$request->file,$destination);   
            if(moduleStatusCheck('University')){
                $visitor->un_academic_id = getAcademicId();
            }  
            $visitor->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('visitor');
        }catch (\Exception $e) {
          Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try{
            $visitor = SmVisitor::find($request->id);
            if ($visitor->file != "" && file_exists($visitor->file)) {
                    unlink($visitor->file);
            }
            $visitor->delete();

            Toastr::success('Operation successful', 'Success');
            return redirect('visitor');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function download_files($id){
        try {
            $visitor = SmVisitor::find($id);
            if (file_exists($visitor->file)) {
                return Response::download($visitor->file);
            }
        } catch (\Throwable $th) {
            Toastr::error('File not found', 'Failed');
            return redirect()->back();
        }
    }

    public function visitorDatatable()
    {
        try {
            $visitors = SmVisitor::query();
            return Datatables::of($visitors)
                    ->addIndexColumn()
                    ->addColumn('query_date', function ($row) {
                        return dateConvert(@$row->date);
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="dropdown CRM_dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                        
                                        <div class="dropdown-menu dropdown-menu-right">'.
                            (userPermission('visitor_edit') === true ? '<a class="dropdown-item" href="' . route('visitor_edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .
    
                            (userPermission('visitor_delete') === true ? (Config::get('app.app_sync') ? '<span data-toggle="tooltip" title="Disabled For Demo"><a class="dropdown-item" href="#" >' . app('translator')->get('common.disable') . '</a></span>' :
                                '<a onclick="deleteQueryModal(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteVisitorModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .
                            '</div>
                            </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'date'])
                    ->make(true);
        } catch (\Throwable $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
        
    }
}