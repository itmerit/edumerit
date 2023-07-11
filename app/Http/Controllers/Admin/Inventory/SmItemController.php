<?php

namespace App\Http\Controllers\Admin\Inventory;
use App\SmItem;
use App\SmItemCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Inventory\ItemListRequest;


class SmItemController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index(Request $request)
    {
        try{
            $items = SmItem::with('category')->where('school_id',Auth::user()->school_id)->get();
            $itemCategories = SmItemCategory::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.inventory.itemList', compact('items', 'itemCategories'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(ItemListRequest $request)
    {
        try{
            $items = new SmItem();
            $items->item_name = $request->item_name;
            $items->item_category_id = $request->category_name;
            $items->total_in_stock = 0;
            $items->description = $request->description;
            $items->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $items->un_academic_id = getAcademicId();
            }else{
                $items->academic_id = getAcademicId();
            }
            $items->save();

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
            if (checkAdmin()) {
                $editData = SmItem::find($id);
            }else{
                $editData = SmItem::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $items = SmItem::where('school_id',Auth::user()->school_id)->get();
            $itemCategories = SmItemCategory::where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.inventory.itemList', compact('editData', 'items', 'itemCategories'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(ItemListRequest $request, $id)
    {
        try{
            if (checkAdmin()) {
                $items = SmItem::find($id);
            }else{
                $items = SmItem::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $items->item_name = $request->item_name;
            $items->item_category_id = $request->category_name;
            $items->description = $request->description;
            if(moduleStatusCheck('University')){
                $items->un_academic_id = getAcademicId();
            }
            $items->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('item-list');
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function deleteItemView(Request $request, $id)
    {
        try{
            $title = __('common.are_you_sure_to_detete_this_item');
            $url = route('delete-item',$id);            
            return view('backEnd.modal.delete', compact('id', 'title', 'url'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function deleteItem(Request $request, $id)
    {
        try{
        $tables = \App\tableList::getTableList('item_id', $id);
        try {
            if ($tables==null) {
                if (checkAdmin()) {
                    SmItem::destroy($id);
                }else{
                    SmItem::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
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
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}