<?php

namespace App\Http\Controllers\Admin\Accounts;
use App\YearCheck;
use App\SmIncomeHead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmIncomeHeadController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(){

		try{
			$income_heads = SmIncomeHead::where('school_id',Auth::user()->school_id)->get();
			return view('backEnd.accounts.income_head', compact('income_heads'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}

    }
    public function store(Request $request){
		$request->validate([
			'income_head' => "required|unique:sm_income_heads,name",
		]);
		try{
			$income_head = new SmIncomeHead();
			$income_head->name = $request->income_head;
			$income_head->description = $request->description;
			$income_head->school_id = Auth::user()->school_id;
			$income_head->academic_id = getAcademicId();
		    $income_head->save();

			Toastr::success('Operation successful', 'Success');
			return redirect()->back();

		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
    }

    public function edit($id){

		 try{
			// $income_head = SmIncomeHead::find($id);
			 if (checkAdmin()) {
				$income_head = SmIncomeHead::find($id);
			}else{
				$income_head = SmIncomeHead::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
			}
			$income_heads = SmIncomeHead::where('school_id',Auth::user()->school_id)->get();
			return view('backEnd.accounts.income_head', compact('income_head', 'income_heads'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
    }
    public function update(Request $request){
    	$request->validate([
			'income_head' => "required|unique:sm_income_heads,name,".$request->id,
		]);
		try{
			$fees_discount = SmIncomeHead::find($request->id);
			$fees_discount->name = $request->income_head;
			$fees_discount->description = $request->description;
			$result = $fees_discount->save();
			if($result){
				Toastr::success('Operation successful', 'Success');
                return redirect('income-head');
				// return redirect('income-head')->with('message-success', 'Income Head has been updated successfully');
			}else{
				Toastr::error('Operation Failed', 'Failed');
            	return redirect()->back();
				// return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
			}
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
    }
    public function delete($id){

		try{
			$fees_discount = SmIncomeHead::destroy($id);
			if($fees_discount){
				Toastr::success('Operation successful', 'Success');
				return redirect()->back();
				// return redirect()->back()->with('message-success-delete', 'Income Head has been deleted successfully');
			}else{
				Toastr::error('Operation Failed', 'Failed');
            	return redirect()->back();
				// return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
			}
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
    }
}