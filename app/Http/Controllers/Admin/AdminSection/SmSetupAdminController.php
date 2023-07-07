<?php

namespace App\Http\Controllers\Admin\AdminSection;

use App\tableList;
use App\SmSetupAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\AdminSection\SmAdminSetupRequest;

class SmSetupAdminController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try{
            $admin_setups = SmSetupAdmin::get();
            $admin_setups = $admin_setups->groupBy('type');
            return view('backEnd.admin.setup_admin', compact('admin_setups'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(SmAdminSetupRequest $request)
    {
        try{
            $setup = new SmSetupAdmin();
            $setup->type = $request->type;
            $setup->name = $request->name;
            $setup->description = $request->description;
            $setup->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $setup->un_academic_id = getAcademicId();
            }else{
                $setup->academic_id = getAcademicId();
            }
            $setup->save();

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
            $admin_setup = SmSetupAdmin::find($id);
            $admin_setups = SmSetupAdmin::get();
            $admin_setups = $admin_setups->groupBy('type');
            return view('backEnd.admin.setup_admin', compact('admin_setups', 'admin_setup'));
        }catch (\Exception $e) {        
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(SmAdminSetupRequest $request, $id)
    {
        try{
            $setup = SmSetupAdmin::find($id);
            $setup->type = $request->type;
            $setup->name = $request->name;
            $setup->description = $request->description;
            if(moduleStatusCheck('University')){
                $setup->un_academic_id = getAcademicId();
            }
            $setup->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('setup-admin');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $tables1 = tableList::getTableList('complaint_type', $id);
            $tables2 = tableList::getTableList('complaint_source', $id);
            $tables3 = tableList::getTableList('source', $id);
            $tables4 = tableList::getTableList('reference', $id);
            if ($tables1==null && $tables2==null && $tables3==null && $tables4==null) {                    
                $setup_admin = SmSetupAdmin::destroy($id);
            }else{
                $msg = 'This data already used in  : ' . $tables1 .' '. $tables2 .' '. $tables3 .' '. $tables4 . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}