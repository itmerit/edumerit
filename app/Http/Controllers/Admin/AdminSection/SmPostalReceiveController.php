<?php
namespace App\Http\Controllers\Admin\AdminSection;

use DataTables;
use App\SmPostalReceive;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Admin\AdminSection\SmPostalReceiveRequest;

class SmPostalReceiveController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try{
            return view('backEnd.admin.postal_receive');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(SmPostalReceiveRequest $request)
    {
        try{
            $destination =  'public/uploads/postal/';
            $fileName=fileUpload($request->file,$destination);
            $postal_receive = new SmPostalReceive();
            $postal_receive->from_title = $request->from_title;
            $postal_receive->reference_no = $request->reference_no;
            $postal_receive->address = $request->address;
            $postal_receive->date = date('Y-m-d', strtotime($request->date));
            $postal_receive->note = $request->note;
            $postal_receive->to_title = $request->to_title;
            $postal_receive->file = $fileName;
            $postal_receive->created_by=Auth::user()->id;
            $postal_receive->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $postal_receive->un_academic_id = getAcademicId();
            }else{
                $postal_receive->academic_id = getAcademicId();
            }
            $postal_receive->save();

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
            $postal_receives = SmPostalReceive::get();
            $postal_receive = SmPostalReceive::find($id);
            return view('backEnd.admin.postal_receive', compact('postal_receives', 'postal_receive'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(SmPostalReceiveRequest $request)
    {
        try{
            $destination='public/uploads/postal/';
            $postal_receive = SmPostalReceive::find($request->id);
            $postal_receive->from_title = $request->from_title;
            $postal_receive->reference_no = $request->reference_no;
            $postal_receive->address = $request->address;
            $postal_receive->date = date('Y-m-d', strtotime($request->date));
            $postal_receive->note = $request->note;
            $postal_receive->to_title = $request->to_title;
            $postal_receive->file = fileUpdate($postal_receive->file,$request->file,$destination);
            if(moduleStatusCheck('University')){
                $postal_receive->un_academic_id = getAcademicId();
            }
            $postal_receive->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('postal-receive');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try{
            $postal_receive = SmPostalReceive::find($request->id);
            if ($postal_receive->file != "") {
                if (file_exists($postal_receive->file)) {
                    unlink($postal_receive->file);
                }
            }
            $postal_receive->delete();

            Toastr::success('Operation successful', 'Success');
            return redirect('postal-receive');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function postalReceiveDatatable()
    {
        try{
            $postal_receives = SmPostalReceive::query();
            return Datatables::of($postal_receives)
                    ->addIndexColumn()
                    ->addColumn('query_date', function($row){
                        return dateConvert(@$row->date);
                    })
                    ->addColumn('action', function ($row){
                        $btn = '<div class="dropdown CRM_dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                        
                                <div class="dropdown-menu dropdown-menu-right">'.
                                (userPermission('postal-receive_edit') === true ? '<a class="dropdown-item" href="' . route('postal-receive_edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                                (userPermission('postal-receive_delete') === true ? (Config::get('app.app_sync') ? '<span data-toggle="tooltip" title="Disabled For Demo"><a class="dropdown-item" href="#" >' . app('translator')->get('common.disable') . '</a></span>' :
                                '<a onclick="deleteQueryModal(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deletePostalReceiveModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .

                                (@$row->file != '' ? (userPermission('postal-receive-document') === true ? (@file_exists($row->file) ? (Config::get('app.app_sync') ? '<span data-toggle="tooltip" title="Disabled For Demo"><a class="dropdown-item" href="#" >' . app('translator')->get('common.disable') . '</a></span>' :
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