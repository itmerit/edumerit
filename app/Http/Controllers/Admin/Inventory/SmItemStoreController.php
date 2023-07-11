<?php

namespace App\Http\Controllers\Admin\Inventory;
use App\SmItemStore;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Inventory\ItemStoreRequest;

class SmItemStoreController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try{
            $itemstores = SmItemStore::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.inventory.itemStoreList', compact('itemstores'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(ItemStoreRequest $request)
    {
        try{
            $stores = new SmItemStore();
            $stores->store_name = $request->store_name;
            $stores->store_no = $request->store_no;
            $stores->description = $request->description;
            $stores->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $stores->un_academic_id = getAcademicId();
            }else{
                $stores->academic_id = getAcademicId();
            }
            $stores->save();

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
            $editData = SmItemStore::find($id);
            $itemstores = SmItemStore::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.inventory.itemStoreList', compact('editData', 'itemstores'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(ItemStoreRequest $request, $id)
    {
        try{
            $stores = SmItemStore::find($id);
            $stores->store_name = $request->store_name;
            $stores->store_no = $request->store_no;
            $stores->description = $request->description;
            if(moduleStatusCheck('University')){
                $stores->un_academic_id = getAcademicId();
            }
            $stores->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('item-store');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function deleteStoreView(Request $request, $id)
    {
        try{
            $title = __('inventory.delete_store');
            $url = route('delete-store',$id);
            return view('backEnd.modal.delete', compact('id', 'title', 'url'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function deleteStore(Request $request, $id)
    {
        try{
            $tables = \App\tableList::getTableList('store_id', $id);
            try {
                if ($tables==null) {
                    SmItemStore::destroy($id);

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
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}