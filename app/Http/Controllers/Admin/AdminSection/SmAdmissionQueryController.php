<?php

namespace App\Http\Controllers\Admin\AdminSection;

use DataTables;
use App\SmClass;
use App\SmSetupAdmin;
use App\SmAdmissionQuery;
use Illuminate\Http\Request;
use App\SmAdmissionQueryFollowup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Admin\AdminSection\SmAdmissionQueryRequest;
use App\Http\Requests\Admin\AdminSection\SmAdmissionQuerySearchRequest;
use App\Http\Requests\Admin\AdminSection\SmAdmissionQueryFollowUpRequest;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;

class SmAdmissionQueryController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function index()
    {
        try {
            $classes = SmClass::get();
            $references = SmSetupAdmin::where('type', 4)->get();
            $sources = SmSetupAdmin::where('type', 3)->get();
            return view('backEnd.admin.admission_query', compact('references', 'classes', 'sources'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmAdmissionQueryRequest $request)
    {
        try {
            $admission_query = new SmAdmissionQuery();
            $admission_query->name = $request->name;
            $admission_query->phone = $request->phone;
            $admission_query->email = $request->email;
            $admission_query->address = $request->address;
            $admission_query->description = $request->description;
            $admission_query->date = date('Y-m-d', strtotime($request->date));
            $admission_query->next_follow_up_date = date('Y-m-d', strtotime($request->next_follow_up_date));
            $admission_query->assigned = $request->assigned;
            $admission_query->reference = $request->reference;
            $admission_query->source = $request->source;
            if (moduleStatusCheck('University')) {
                $common = App::make(UnCommonRepositoryInterface::class);
                $data = $common->storeUniversityData($admission_query, $request);
            } else {
                $admission_query->class = $request->class;
                $admission_query->academic_id = getAcademicId();
            }
            $admission_query->no_of_child = $request->no_of_child;
            $admission_query->created_by = Auth::user()->id;
            $admission_query->school_id = Auth::user()->school_id;
            $admission_query->save();
           
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $data = [];
            $admission_query = SmAdmissionQuery::find($id);
            $classes = SmClass::get();
            $references = SmSetupAdmin::where('type', 4)->get();
            $sources = SmSetupAdmin::where('type', 3)->get();
            if (moduleStatusCheck('University')) {
                $common = App::make(UnCommonRepositoryInterface::class);
                $data = $common->getCommonData($admission_query);
            }
            return view('backEnd.admin.admission_query_edit', compact('admission_query', 'references', 'classes', 'sources'))->with($data);
        } catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmAdmissionQueryRequest $request)
    {
        try {
            if (checkAdmin()) {
                $admission_query = SmAdmissionQuery::find($request->id);
            }else{
                 $admission_query = SmAdmissionQuery::where('created_by',auth()->user()->id)->where('id',$request->id)->first();
            }
            $admission_query->name = $request->name;
            $admission_query->phone = $request->phone;
            $admission_query->email = $request->email;
            $admission_query->address = $request->address;
            $admission_query->description = $request->description;
            $admission_query->date = date('Y-m-d', strtotime($request->date));
            $admission_query->next_follow_up_date = date('Y-m-d', strtotime($request->next_follow_up_date));
            $admission_query->assigned = $request->assigned;
            if ($request->reference) {
                $admission_query->reference = $request->reference;
            }
            $admission_query->source = $request->source;
            if (moduleStatusCheck('University')) {
                $common = App::make(UnCommonRepositoryInterface::class);
                $data = $common->storeUniversityData($admission_query, $request);
            } else {
                $admission_query->class = $request->class;
            }
            $admission_query->no_of_child = $request->no_of_child;
            $admission_query->school_id = Auth::user()->school_id;
            $admission_query->academic_id = getAcademicId();
            $admission_query->save();
         
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function addQuery($id)
    {
        try {
            $admission_query = SmAdmissionQuery::where('school_id', auth()->user()->school_id)->where('id', $id)->first();
            $follow_up_lists = SmAdmissionQueryFollowup::where('academic_id', getAcademicId())->where('admission_query_id', $id)->orderby('id', 'DESC')->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $references = SmSetupAdmin::where('type', 4)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            $sources = SmSetupAdmin::where('type', 3)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.admin.add_query', compact('admission_query', 'follow_up_lists', 'references', 'classes', 'sources'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function queryFollowupStore(SmAdmissionQueryFollowUpRequest $request)
    {
        DB::beginTransaction();
        try {
            $admission_query = SmAdmissionQuery::find($request->id);
            $admission_query->follow_up_date = date('Y-m-d', strtotime($request->follow_up_date));
            $admission_query->next_follow_up_date = date('Y-m-d', strtotime($request->next_follow_up_date));
            $admission_query->active_status = $request->status;
            $admission_query->school_id = Auth::user()->school_id;
            $admission_query->academic_id = getAcademicId();
            $admission_query->save();
            $admission_query->toArray();

            $follow_up = new SmAdmissionQueryFollowup();
            $follow_up->admission_query_id = $admission_query->id;
            $follow_up->response = $request->response;
            $follow_up->note = $request->note;
            $follow_up->created_by =Auth::user()->id;
            $follow_up->school_id = Auth::user()->school_id;
            $follow_up->academic_id = getAcademicId();
            $follow_up->save();
            DB::commit();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback(); 
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteFollowUp($id)
    {
        try { 
            SmAdmissionQueryFollowup::destroy($id);

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $admission_query = SmAdmissionQuery::find($request->id);
            SmAdmissionQueryFollowup::where('admission_query_id', $admission_query->id)->delete();
            $admission_query->delete();
            DB::commit();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function admissionQuerySearch(SmAdmissionQuerySearchRequest $request)
    {
        try {
            $requestData = [];
            $date_from = date('Y-m-d', strtotime($request->date_from));
            $date_to = date('Y-m-d', strtotime($request->date_to));
            $requestData['date_from'] = $request->date_from;
            $requestData['date_to'] = $request->date_to;
            $requestData['source'] = $request->source;
            $requestData['status'] = $request->status;

            $date_from = $request->date_from;
            $date_to = $request->date_to;
            $source_id = $request->source;
            $status_id = $request->status;
            $classes = SmClass::get();
            $references = SmSetupAdmin::where('type', 4)->get();
            $sources = SmSetupAdmin::where('type', 3)->get();
            return view('backEnd.admin.admission_query', compact('requestData', 'references', 'classes', 'sources', 'date_from', 'date_to', 'source_id', 'status_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function admissionQueryDatatable(Request $request)
    {
        try{
            if ($request->ajax()) 
            {
                $date_from = date('Y-m-d', strtotime($request->date_from));
                $date_to = date('Y-m-d', strtotime($request->date_to));
                $admission_queries = SmAdmissionQuery::query();
                $admission_queries->with('sourceSetup', 'className', 'user', 'referenceSetup')->orderBy('id', 'DESC');
                if ($request->date_from != "" && $request->date_to) {
                    $admission_queries->where('date', '>=', $date_from)->where('date', '<=', $date_to);
                }
                if ($request->source != "") {
                    $admission_queries->where('source', $request->source);
                }
                if ($request->status != "") {
                    $admission_queries->where('active_status', $request->status);
                }
                return Datatables::of($admission_queries)
                        ->addIndexColumn()
                        ->addColumn('query_date', function ($row) {
                            return dateConvert(@$row->date);
                        })
                        ->addColumn('follow_up_date', function ($row) {
                            return dateConvert(@$row->follow_up_date);
                        })
                        ->addColumn('next_follow_up_date', function ($row) {
                            return dateConvert(@$row->next_follow_up_date);
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<div class="dropdown CRM_dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                            
                                            <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item"
                                            href="' . route('add_query', [@$row->id]) . '">' . __('admin.add_query'). '</a>'.
                                (userPermission('admission_query_edit') === true ? '<a class="dropdown-item modalLink" data-modal-size="large-modal"
                                title="' . __('admin.edit_admission_query'). '" href="' . route('admission_query_edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .
    
                                (userPermission('admission_query_delete') === true ? (Config::get('app.app_sync') ? '<span data-toggle="tooltip" title="Disabled For Demo"><a class="dropdown-item" href="#" >' . app('translator')->get('common.disable') . '</a></span>' :
                                    '<a onclick="deleteQueryModal(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteAdmissionQueryModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .
                                '</div>
                                </div>';
                            return $btn;
                        })
                        ->rawColumns(['action', 'date'])
                        ->make(true);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}