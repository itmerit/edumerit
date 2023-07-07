<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\SmSupplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Inventory\SmSupplierRequest;

class SmSupplierController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}
    public function index(Request $request)
    {
        try{
            $suppliers = SmSupplier::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.inventory.supplierList', compact('suppliers'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(SmSupplierRequest $request)
    {
        try{
            $suppliers = new SmSupplier();
            $suppliers->company_name = $request->company_name;
            $suppliers->company_address = $request->company_address;
            $suppliers->contact_person_name = $request->contact_person_name;
            $suppliers->contact_person_mobile = $request->contact_person_mobile;
            $suppliers->contact_person_email = $request->contact_person_email;
            $suppliers->description = $request->description;
            $suppliers->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $suppliers->un_academic_id = getAcademicId();
            }else{
                $suppliers->academic_id = getAcademicId();
            }
            $suppliers->save();

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
            $editData = SmSupplier::find($id);
            $suppliers = SmSupplier::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.inventory.supplierList', compact('editData', 'suppliers'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(SmSupplierRequest $request, $id)
    {
        try{
            $suppliers = SmSupplier::find($id);
            $suppliers->company_name = $request->company_name;
            $suppliers->company_address = $request->company_address;
            $suppliers->contact_person_name = $request->contact_person_name;
            $suppliers->contact_person_mobile = $request->contact_person_mobile;
            $suppliers->contact_person_email = $request->contact_person_email;
            $suppliers->description = $request->description;
            $suppliers->updated_by = Auth()->user()->id;
            if(moduleStatusCheck('University')){
                $suppliers->un_academic_id = getAcademicId();
            }
            $suppliers->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('suppliers');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function deleteSupplierView(Request $request, $id)
    {
        try{
            $title = __('common.are_you_sure_to_detete_this_item');
            $url = route('delete-supplier',$id);
            return view('backEnd.modal.delete', compact('id', 'title', 'url'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function deleteSupplier(Request $request, $id)
    {
        try{
            $tables = \App\tableList::getTableList('supplier_id', $id);
            try {
                if ($tables==null) {
                    $result = SmSupplier::destroy($id);

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
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}