<?php

namespace App\Http\Controllers\Admin\Accounts;

use DataTables;
use Carbon\Carbon;
use App\SmAddIncome;
use App\SmBankAccount;
use App\SmBankStatement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Admin\Accounts\SmBankAccountRequest;

class SmBankAccountController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function index()
    {
        try{
            return view('backEnd.accounts.bank_account');
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function create()
    {
        
    }

    public function store(SmBankAccountRequest $request)
    {
      try{
       
            $bank_account = new SmBankAccount();
            $bank_account->bank_name = $request->bank_name;
            $bank_account->account_name = $request->account_name;
            $bank_account->account_number = $request->account_number;
            $bank_account->account_type = $request->account_type;
            $bank_account->opening_balance = $request->opening_balance;
            $bank_account->current_balance = $request->opening_balance;
            $bank_account->note = $request->note;
            $bank_account->active_status = 1;
            $bank_account->created_by=auth()->user()->id;
            if(moduleStatusCheck('University')){
                $bank_account->un_academic_id = getAcademicId();
            }else{
                $bank_account->academic_id = getAcademicId();
            }
            $bank_account->school_id = Auth::user()->school_id;
            $bank_account->save();

            $add_income = new SmAddIncome();
            $add_income->name = 'Opening Balance';
            $add_income->date =Carbon::now();
            $add_income->amount = $request->opening_balance;
            $add_income->item_sell_id = $bank_account->id;
            $add_income->active_status = 1;
            $add_income->created_by = Auth()->user()->id;
            $add_income->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $add_income->un_academic_id = getAcademicId();
            }else{
                $add_income->academic_id = getAcademicId();
            }
            $add_income->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        try{
            $bank_account = SmBankAccount::find($id);            
            $bank_accounts = SmBankAccount::get();
            return view('backEnd.accounts.bank_account', compact('bank_accounts', 'bank_account'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }

    public function update(SmBankAccountRequest $request, $id)
    {
        try{
            $bank_account = SmBankAccount::find($request->id);
            $bank_account->bank_name = $request->bank_name;
            $bank_account->account_name = $request->account_name;
            $bank_account->account_number = $request->account_number;
            $bank_account->account_type = $request->account_type;
            $bank_account->opening_balance = $request->opening_balance;
            $bank_account->note = $request->note;
            if(moduleStatusCheck('University')){
                $bank_account->un_academic_id = getAcademicId();
            }else{
                $bank_account->academic_id = getAcademicId();
            }
            $bank_account->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('bank-account');
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function bankTransaction($id){
        $bank_name=SmBankAccount::where('id',$id)->firstOrFail();
        $bank_transactions=SmBankStatement::where('bank_id',$id)->get();
        return view('backEnd.accounts.bank_transaction',compact('bank_transactions','bank_name'));
    }

    public function destroy(Request $request)
    {
        try{
            $tables = \App\tableList::getTableList('bank_id', $request->id);
            try {
                if ($tables==null) {
                    $bank_account = SmBankAccount::destroy($request->id);

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
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function bankAccountDatatable()
    {
        try{
            $bank_accounts = SmBankAccount::query();
            return DataTables::of($bank_accounts)
                    ->addIndexColumn()
                    ->addColumn('opening_balance', function($row){
                        return currency_format(@$row->opening_balance);
                    })
                    ->addColumn('current_balance', function($row){
                        return currency_format(@$row->current_balance);
                    })
                    ->addColumn('action', function ($row){
                        $btn = '<div class="dropdown CRM_dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                        
                                <div class="dropdown-menu dropdown-menu-right">'.
                                (userPermission('bank-transaction') === true ? '<a class="dropdown-item" href="' . route('bank-transaction', [$row->id]) . '">' . __('accounts.transaction') . '</a>' : '') .

                                (userPermission('bank-account-delete') === true ? (Config::get('app.app_sync') ? '<span data-toggle="tooltip" title="Disabled For Demo"><a class="dropdown-item" href="#" >' . app('translator')->get('common.disable') . '</a></span>' :
                                '<a onclick="deleteBankModal(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteBankAccountModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .
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