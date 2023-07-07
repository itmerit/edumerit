<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\tableList;
use App\SmChartOfAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Accounts\SmChartOfAccountRequest;

class SmChartOfAccountController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index()
    {
        try {
            $chart_of_accounts = SmChartOfAccount::get();
            return view('backEnd.accounts.chart_of_account', compact('chart_of_accounts'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmChartOfAccountRequest $request)
    {
        try {
            $chart_of_account = new SmChartOfAccount();
            $chart_of_account->head = $request->head;
            $chart_of_account->type = $request->type;
            $chart_of_account->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $chart_of_account->un_academic_id = getAcademicId();
            }else{
                $chart_of_account->academic_id = getAcademicId();
            }
            $chart_of_account->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
             ;
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        try {
            $chart_of_account = SmChartOfAccount::find($id);
            $chart_of_accounts = SmChartOfAccount::get();
            return view('backEnd.accounts.chart_of_account', compact('chart_of_account', 'chart_of_accounts'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmChartOfAccountRequest $request, $id)
    {
        try {
            $chart_of_account = SmChartOfAccount::find($request->id);
            $chart_of_account->head = $request->head;
            $chart_of_account->type = $request->type;
            if(moduleStatusCheck('University')){
                $chart_of_account->un_academic_id = getAcademicId();
            }
            $chart_of_account->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->route('chart-of-account');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try{
            $tables1 = tableList::getTableList('income_head_id', $id);
            $tables2 = tableList::getTableList('expense_head_id', $id);
            try {
                if ($tables1 ==null && $tables2 ==null){
                    $chart_of_account = SmChartOfAccount::destroy($id);

                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }else{
                    $msg = 'This data already used in  : ' . $tables1 .' '. $tables2 .' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {
                $msg = 'This data already used in  : '. $tables1 .' '. $tables2 .' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}