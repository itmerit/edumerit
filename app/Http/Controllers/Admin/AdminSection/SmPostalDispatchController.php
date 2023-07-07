<?php

namespace App\Http\Controllers\Admin\AdminSection;

use DataTables;
use App\SmPostalDispatch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Admin\AdminSection\SmPostalDispatchRequest;


class SmPostalDispatchController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try{
            return view('backEnd.admin.postal_dispatch');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(SmPostalDispatchRequest $request)
    {
        try{
            $destination =  'public/uploads/postal/';
            $fileName=fileUpload($request->image,$destination);
            $postal_dispatch = new SmPostalDispatch();
            $postal_dispatch->from_title = $request->from_title;
            $postal_dispatch->reference_no = $request->reference_no;
            $postal_dispatch->address = $request->address;
            $postal_dispatch->date = date('Y-m-d', strtotime($request->date));
            $postal_dispatch->note = $request->note;
            $postal_dispatch->to_title = $request->to_title;
            $postal_dispatch->file = $fileName;
            $postal_dispatch->created_by=Auth::user()->id;
            $postal_dispatch->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $postal_dispatch->un_academic_id = getAcademicId();
            }else{
                $postal_dispatch->academic_id = getAcademicId();
            }
            $postal_dispatch->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function show(Request $request, $id)
    {
        try{
            $postal_dispatchs = SmPostalDispatch::get(); 
            $postal_dispatch = SmPostalDispatch::find($id);
            return view('backEnd.admin.postal_dispatch', compact('postal_dispatchs', 'postal_dispatch'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(SmPostalDispatchRequest $request)
    {
        try{
            $destination='public/uploads/postal/' ;
            $postal_dispatch = SmPostalDispatch::find($request->id);

            $postal_dispatch->from_title = $request->from_title;
            $postal_dispatch->reference_no = $request->reference_no;
            $postal_dispatch->address = $request->address;
            $postal_dispatch->date = date('Y-m-d', strtotime($request->date));
            $postal_dispatch->note = $request->note;
            $postal_dispatch->to_title = $request->to_title;            
            $postal_dispatch->file =fileUpdate($postal_dispatch->file,$request->file,$destination); 
            if(moduleStatusCheck('University')){
                $postal_dispatch->un_academic_id = getAcademicId();
            }         
            $postal_dispatch->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('postal-dispatch');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try{
            $postal_dispatch = SmPostalDispatch::find($request->id);
            if ($postal_dispatch->file != "") {
                if (file_exists($postal_dispatch->file)) {
                    unlink($postal_dispatch->file);
                }
            }
            $postal_dispatch->delete();

            Toastr::success('Operation successful', 'Success');
            return redirect('postal-dispatch');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function postalDispatchDatatable()
    {
        try {
            $postal_dispatchs = SmPostalDispatch::query();
            return DataTables::of($postal_dispatchs)
                    ->addIndexColumn()
                    ->addColumn('query_date', function($row){
                        return dateConvert(@$row->date);
                    })
                    ->addColumn('action', function ($row){
                        $btn = '<div class="dropdown CRM_dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                        
                                <div class="dropdown-menu dropdown-menu-right">'.
                                (userPermission('postal-dispatch_edit') === true ? '<a class="dropdown-item" href="' . route('postal-dispatch_edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                                (userPermission('postal-dispatch_delete') === true ? (Config::get('app.app_sync') ? '<span data-toggle="tooltip" title="Disabled For Demo"><a class="dropdown-item" href="#" >' . app('translator')->get('common.disable') . '</a></span>' :
                                '<a onclick="deleteQueryModal(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteDispatchReceiveModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .

                                (@$row->file != '' ? (userPermission('postal-dispatch-document') === true ? (@file_exists($row->file) ? (Config::get('app.app_sync') ? '<span data-toggle="tooltip" title="Disabled For Demo"><a class="dropdown-item" href="#" >' . app('translator')->get('common.disable') . '</a></span>' :
                                '<a class="dropdown-item" href="' . url(@$row->file) . '">' . app('translator')->get('common.download') . '</a>') : '') : '') : '') .
                            '</div>
                            </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'date'])
                    ->make(true);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
