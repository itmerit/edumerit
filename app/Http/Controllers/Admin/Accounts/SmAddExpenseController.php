<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\SmAddExpense;
use App\ApiBaseMethod;
use App\SmBankAccount;
use App\SmBankStatement;
use App\SmChartOfAccount;
use App\SmPaymentMethhod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Accounts\SmExpenseRequest;

class SmAddExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function index(Request $request)
    {
        try {
            $add_expenses    = SmAddExpense::with('expenseHead','ACHead','paymentMethod','account')->get();
            $expense_heads   = SmChartOfAccount::where('type', "E")->get(['head','id']);
            $bank_accounts   = SmBankAccount::get();
            $payment_methods = SmPaymentMethhod::get(['method','id']);
            return view('backEnd.accounts.add_expense', compact('add_expenses', 'expense_heads', 'bank_accounts', 'payment_methods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmExpenseRequest $request)
    {
        try {
            $destination='public/uploads/addExpense/';
           // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $add_expense = new SmAddExpense();
            $add_expense->name = $request->name;
            $add_expense->expense_head_id = $request->expense_head;
            $add_expense->date = date('Y-m-d', strtotime($request->date));
            $add_expense->payment_method_id = $request->payment_method;
            if (paymentMethodName($request->payment_method)) {
                $add_expense->account_id = $request->accounts;
            }
            $add_expense->amount = $request->amount;
            $add_expense->file = fileUpload($request->file,$destination);
            $add_expense->description = $request->description;
            $add_expense->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $add_expense->un_academic_id = getAcademicId();
            }else{
                $add_expense->academic_id = getAcademicId();
            }
            $result = $add_expense->save();

            if(paymentMethodName($request->payment_method)){
                $bank=SmBankAccount::where('id',$request->accounts)
                ->where('school_id',Auth::user()->school_id)
                ->first();
                $after_balance= $bank->current_balance - $request->amount;

                $bank_statement= new SmBankStatement();
                $bank_statement->amount= $request->amount;
                $bank_statement->after_balance= $after_balance;
                $bank_statement->type= 0;
                $bank_statement->details= $request->name;
                $bank_statement->item_receive_id= $add_expense->id;
                $bank_statement->payment_date= date('Y-m-d',strtotime($request->date));
                $bank_statement->bank_id= $request->accounts;
                $bank_statement->school_id=Auth::user()->school_id;
                $bank_statement->payment_method= $request->payment_method;
                $bank_statement->save();

                $current_balance= SmBankAccount::find($request->accounts);
                $current_balance->current_balance=$after_balance;
                $current_balance->update();
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    
    public function show(Request $request, $id)
    {
        try {
            $add_expense = SmAddExpense::find($id);
            $add_expenses    = SmAddExpense::with('expenseHead','ACHead','paymentMethod','account')->get();
            $expense_heads   = SmChartOfAccount::where('type', "E")->get(['head','id']);
            $bank_accounts   = SmBankAccount::get();
            $payment_methods = SmPaymentMethhod::get(['method','id']);
            return view('backEnd.accounts.add_expense', compact('add_expenses', 'add_expense', 'expense_heads', 'bank_accounts', 'payment_methods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmExpenseRequest $request, $id)
    {
        try {
            $destination =  'public/uploads/addExpense/';
           // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
             if (checkAdmin()) {
                    $add_expense = SmAddExpense::find($request->id);
                }else{
                    $add_expense = SmAddExpense::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $add_expense->name = $request->name;
            $add_expense->expense_head_id = $request->expense_head;
            $add_expense->date = date('Y-m-d', strtotime($request->date));
            $add_expense->payment_method_id = $request->payment_method;
            if (paymentMethodName($request->payment_method)) {
                $add_expense->account_id = $request->accounts;
            }
            $add_expense->amount = $request->amount;
            $add_expense->file = fileUpdate($add_expense->file,$request->file,$destination);
            $add_expense->school_id = Auth::user()->school_id;
            $add_expense->description = $request->description;
            if(moduleStatusCheck('University')){
                $add_expense->un_academic_id = getAcademicId();
            }else{
                $add_expense->academic_id = getAcademicId();
            }
            $result = $add_expense->save();

            if(paymentMethodName($request->payment_method)){
                SmBankStatement::where('item_receive_id', $request->id)
                                    ->where('school_id',Auth::user()->school_id)
                                    ->delete();
                $bank=SmBankAccount::where('id',$request->accounts)
                                ->where('school_id',Auth::user()->school_id)
                                ->first();
                $after_balance= $bank->current_balance - $request->amount;

                $bank_statement= new SmBankStatement();
                $bank_statement->amount= $request->amount;
                $bank_statement->after_balance= $after_balance;
                $bank_statement->type= 0;
                $bank_statement->details= $request->name;
                $bank_statement->item_receive_id= $add_expense->id;
                $bank_statement->payment_date= date('Y-m-d',strtotime($request->date));
                $bank_statement->bank_id= $request->accounts;
                $bank_statement->school_id= Auth::user()->school_id;
                $bank_statement->payment_method= $request->payment_method;
                $bank_statement->save();

                $current_balance= SmBankAccount::find($request->accounts);
                $current_balance->current_balance=$after_balance;
                $current_balance->update();
            }

            Toastr::success('Operation successful', 'Success');
            return redirect('add-expense');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $id = $request->id;
            $add_expense = SmAddExpense::find($id);
            if ($add_expense->file != "") {
                unlink($add_expense->file);
            }
           // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            if(paymentMethodName($add_expense->payment_method_id)){
                $reset_balance = SmBankStatement::where('item_receive_id',$add_expense->account_id)
                                ->where('school_id',Auth::user()->school_id)
                                ->sum('amount');

                $bank=SmBankAccount::where('id',$add_expense->account_id)
                                ->where('school_id',Auth::user()->school_id)
                                ->first();
                $after_balance= $bank->current_balance + $reset_balance;

                $current_balance= SmBankAccount::find($add_expense->account_id);
                $current_balance->current_balance=$after_balance;
                $current_balance->update();

                SmBankStatement::where('item_receive_id',$id)
                                ->where('school_id',Auth::user()->school_id)
                                ->delete();
            }
            $add_expense->delete();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}