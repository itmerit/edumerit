<?php

namespace App\Http\Controllers\Admin\Accounts;

use Carbon\Carbon;
use App\SmItemSell;
use App\SmAddIncome;
use App\SmAddExpense;
use App\SmFeesMaster;
use App\ApiBaseMethod;
use App\SmBankAccount;
use App\SmFeesPayment;
use App\SmItemReceive;
use App\SmBankStatement;
use App\SmAmountTransfer;
use App\SmPaymentMethhod;
use App\SmHrPayrollGenerate;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Accounts\SmProfitLossRequest;
use App\Http\Requests\Admin\Accounts\SmFundTransferRequest;

class SmAccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function searchAccount()
    {
        try {
            return view('backEnd.accounts.search_income');
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function searchAccountReportByDate(Request $request)
    {
        $request->validate([
            'type' => 'required'
        ]);
        try {
            $date_from = date('Y-m-d', strtotime($request->date_from));
            $date_to = date('Y-m-d', strtotime($request->date_to));
            $date_time_from = date('Y-m-d H:i:s', strtotime($request->date_from));
            $date_time_to = date('Y-m-d H:i:s', strtotime($request->date_to . ' ' . '23:59:00'));
            $type_id = $request->type;
            $from_date = $request->date_from;
            $to_date = $request->date_to;
            if ($request->type == "In") {
                if ($request->filtering_income == "all") {
                    $dormitory = 0;
                    $transport = 0;
                    $add_incomes = SmAddIncome::where('date', '>=', $date_from)
                        ->where('date', '<=', $date_to)
                        ->where('active_status', 1)
                        ->where('school_id', Auth::user()->school_id)
                        ->get();

                    $fees_payments = SmFeesPayment::where('updated_at', '>=', $date_time_from)
                        ->where('updated_at', '<=', $date_time_to)
                        ->where('active_status', 1)
                        ->where('school_id', Auth::user()->school_id)
                        ->sum('amount');

                    $item_sells = SmItemSell::where('updated_at', '>=', $date_time_from)
                        ->where('updated_at', '<=', $date_time_to)
                        ->where('active_status', 1)
                        ->where('school_id', Auth::user()->school_id)
                        ->sum('total_paid');
                } elseif ($request->filtering_income == "sell") {
                    $dormitory = 0;
                    $transport = 0;
                    $add_incomes = [];
                    $fees_payments = '';
                    $item_sells = SmItemSell::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->sum('total_paid');
                } elseif ($request->filtering_income == "fees") {
                    $dormitory = 0;
                    $add_incomes = [];
                    $transport = 0;
                    $item_sells = '';
                    $fees_payments = SmFeesPayment::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->sum('amount');
                } elseif ($request->filtering_income == "dormitory") {
                    $add_incomes = [];
                    $fees_payments = '';
                    $item_sells = '';
                    $transport = 0;
                    $fees_masters = SmFeesMaster::select('fees_type_id')->Where('fees_group_id', 2)->where('school_id', Auth::user()->school_id)->get();
                    $dormitory = 0;
                    foreach ($fees_masters as $fees_master) {
                        $dormitory = $dormitory + SmFeesPayment::where('fees_type_id', $fees_master->fees_type_id)->where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->sum('amount');
                    }
                } else {
                    $add_incomes = [];
                    $fees_payments = '';
                    $item_sells = '';
                    $dormitory = 0;
                    $fees_masters = SmFeesMaster::select('fees_type_id')->Where('fees_group_id', 1)->where('school_id', Auth::user()->school_id)->get();
                    $transport = 0;
                    foreach ($fees_masters as $fees_master) {
                        $transport = $transport + SmFeesPayment::where('fees_type_id', $fees_master->fees_type_id)->where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->sum('amount');
                    }
                }

                return view('backEnd.accounts.search_income', compact('add_incomes', 'fees_payments', 'item_sells', 'dormitory', 'transport', 'type_id', 'from_date', 'to_date'));
            } else {
                if ($request->filtering_expense == "all") {
                    $add_expenses = SmAddExpense::where('date', '>=', $date_from)->where('date', '<=', $date_to)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                    $item_receives = SmItemReceive::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->sum('total_paid');
                    $payroll_payments = SmHrPayrollGenerate::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('payroll_status', 'P')->where('school_id', Auth::user()->school_id)->sum('net_salary');
                } elseif ($request->filtering_expense == "receive") {
                    $add_expenses = [];
                    $item_receives = SmItemReceive::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->sum('total_paid');
                    $payroll_payments = '';
                } else {
                    $add_expenses = [];
                    $item_receives = '';
                    $payroll_payments = SmItemReceive::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->sum('total_paid');
                }
                return view('backEnd.accounts.search_income', compact('add_expenses', 'item_receives', 'payroll_payments', 'type_id', 'from_date', 'to_date'));
            }
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function searchExpense()
    {
        try {
            return view('backEnd.accounts.search_expense');
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function searchExpenseReportByDate(Request $request)
    {
        try {
            date_default_timezone_set("Asia/Dhaka");

            $date_from = date('Y-m-d', strtotime($request->date_from));
            $date_to = date('Y-m-d', strtotime($request->date_to));

            $date_time_from = date('Y-m-d H:i:s', strtotime($request->date_from));
            $date_time_to = date('Y-m-d H:i:s', strtotime($request->date_to . ' ' . '23:59:00'));

            $add_expenses = SmAddExpense::where('date', '>=', $date_from)->where('date', '<=', $date_to)->where('active_status', 1)->get();

            $item_receives = SmItemReceive::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->sum('total_paid');
            $payroll_payments = SmHrPayrollGenerate::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('payroll_status', 'P')->where('school_id', Auth::user()->school_id)->sum('net_salary');
            return view('backEnd.accounts.search_expense', compact('add_expenses', 'item_receives', 'payroll_payments'));
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function profit(Request $request)
    {       
        try {
            $add_incomes = SmAddIncome::where('active_status', 1)
                ->where('name', '!=', "Fund Transfer")
                ->where('school_id', Auth::user()->school_id)
                ->sum('amount');

            $total_income = $add_incomes;

            $add_expenses = SmAddExpense::where('active_status', 1)
                ->where('name', '!=', "Fund Transfer")
                ->where('school_id', Auth::user()->school_id)
                ->sum('amount');

            $total_expense = $add_expenses;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['total_income'] = $total_income;
                $data['total_expense'] = $total_expense;
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.accounts.profit', compact('total_income', 'total_expense'));
        } catch (Exception $e) {
            
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function searchProfitByDate(SmProfitLossRequest $request)
    {
        try {
            date_default_timezone_set("Asia/Dhaka");

            $rangeArr = $request->date_range ? explode('-', $request->date_range) : "" . date('m/d/Y') . " - " . date('m/d/Y') . "";

            if ($request->date_range) {
                $date_from = new DateTime(trim($rangeArr[0]));
                $date_to = new DateTime(trim($rangeArr[1]));
            }

            $date_time_from = date('Y-m-d H:i:s', strtotime($rangeArr[0]));
            $date_time_to = date('Y-m-d H:i:s', strtotime($rangeArr[1] . ' ' . '23:59:00'));

           
            // Income
            $add_incomes = SmAddIncome::where('date', '>=', $date_from)
                ->where('date', '<=', $date_to)
                ->where('active_status', 1)
                ->where('school_id', Auth::user()->school_id)
                ->sum('amount');

            $total_income = $add_incomes;

            // expense
            $add_expenses = SmAddExpense::where('date', '>=', $date_from)
                ->where('date', '<=', $date_to)
                ->where('active_status', 1)
                ->where('school_id', Auth::user()->school_id)
                ->sum('amount');

            $total_expense = $add_expenses;

            return view('backEnd.accounts.profit', compact('total_income', 'total_expense', 'date_time_from', 'date_time_to'));
        } catch (Exception $e) {
        
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function transaction()
    {
        try {
            $payment_methods = SmPaymentMethhod::where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.accounts.transaction', compact('payment_methods'));
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function transactionSearch(Request $request)
    {
        try {
            $rangeArr = $request->date_range ? explode('-', $request->date_range) : "" . date('m/d/Y') . " - " . date('m/d/Y') . "";
            if ($request->date_range) {
                $date_from = new DateTime(trim($rangeArr[0]));
                $date_to = new DateTime(trim($rangeArr[1]));
            }
            $payment_methods = SmPaymentMethhod::where('school_id', Auth::user()->school_id)->get();
            $payment_method = $request->payment_method;

            if ($request->payment_method != "all") {
                $method_id = SmPaymentMethhod::find($request->payment_method);
                $search_info['method_id'] = $method_id->id;
            }


            if ($request->date_range && $request->type == "all" && $request->payment_method == "all") {
                $add_incomes = SmAddIncome::where('date', '>=', $date_from)
                    ->where('date', '<=', $date_to)
                    ->where('active_status', 1)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
                $add_expenses = SmAddExpense::where('date', '>=', $date_from)
                    ->where('date', '<=', $date_to)
                    ->where('active_status', 1)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
                return view('backEnd.accounts.transaction', compact('payment_methods', 'add_incomes', 'add_expenses'));
            } elseif ($request->date_range && $request->type == "In") {
                if ($request->payment_method == 1 || $request->payment_method == 2 || $request->payment_method == 3 || $request->payment_method == 4 || $request->payment_method == 5) {
                    $add_incomes = SmAddIncome::addIncome($date_from, $date_to, $payment_method)->get();
                    return view('backEnd.accounts.transaction', compact('payment_methods', 'add_incomes', 'search_info'));
                } else {
                    $add_incomes = SmAddIncome::where('date', '>=', $date_from)
                        ->where('date', '<=', $date_to)
                        ->where('active_status', 1)
                        ->where('school_id', Auth::user()->school_id)
                        ->get();
                    return view('backEnd.accounts.transaction', compact('payment_methods', 'add_incomes'));
                }
            } elseif ($request->date_range && $request->type == "Ex") {
                if ($request->payment_method == 1 || $request->payment_method == 2 || $request->payment_method == 3 || $request->payment_method == 4 || $request->payment_method == 5) {
                    $add_expenses = SmAddExpense::addExpense($date_from, $date_to, $payment_method)->get();
                    return view('backEnd.accounts.transaction', compact('payment_methods', 'add_expenses', 'search_info'));
                } else{
                    $add_expenses = SmAddExpense::where('date', '>=', $date_from)
                        ->where('date', '<=', $date_to)
                        ->where('active_status', 1)
                        ->where('school_id', Auth::user()->school_id)
                        ->get();
                    return view('backEnd.accounts.transaction', compact('payment_methods', 'add_expenses'));
                }
            }
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function accountsPayrollReport(Request $request)
    {
        try {
            return view('backEnd.accounts.accounts_payroll_report');
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function accountsPayrollReportSearch(Request $request)
    {
        try {
            $rangeArr = $request->date_range ? explode('-', $request->date_range) : "" . date('m/d/Y') . " - " . date('m/d/Y') . "";
            if ($request->date_range) {
                $date_from = new DateTime(trim($rangeArr[0]));
                $date_to = new DateTime(trim($rangeArr[1]));
            }

            $payroll_infos = SmAddExpense::where('date', '>=', $date_from)
                ->where('date', '<=', $date_to)
                ->where('active_status', 1)
                ->where('name', "Staff Payroll")
                ->where('school_id', Auth::user()->school_id)
                ->get();

            return view('backEnd.accounts.accounts_payroll_report', compact('payroll_infos'));
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function fundTransfer()
    {
        try {
            $payment_methods = SmPaymentMethhod::get(['method', 'id']);
            $bank_accounts = SmBankAccount::get();
            $transfers = SmAmountTransfer::where('school_id', Auth::user()->school_id)->get();
            $bank_amount = SmBankAccount::sum('current_balance');
            return view('backEnd.accounts.fund_transfer', compact('payment_methods', 'bank_accounts', 'transfers', 'bank_amount'));
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function fundTransferStore(SmFundTransferRequest $request)
    {

        try {
            // Validation Part Start
            if ($request->from_payment_method == 3 && $request->from_bank_name == '') {
                Toastr::error('Bank Name is Required', 'Error');
                return redirect()->back();
            } elseif ($request->to_payment_method_name == 3 && $request->to_bank_name == '') {
                Toastr::error('Bank Name is Required', 'Error');
                return redirect()->back();
            }

            if ($request->from_payment_method == 3 && $request->from_bank_name == $request->to_bank_name) {
                $message = SmBankAccount::where('id', $request->from_bank_name)
                    ->where('school_id', Auth::user()->school_id)
                    ->first();

                Toastr::warning($message->bank_name . ' to ' . $message->bank_name . ' transfer is not accepted', 'Warning');
                return redirect()->back();
            } elseif ($request->from_payment_method == $request->to_payment_method) {
                if ($request->from_payment_method != 3) {
                    $message = SmPaymentMethhod::where('id', $request->from_payment_method)
                        ->where('school_id', Auth::user()->school_id)
                        ->first();
                    Toastr::warning(@$message->method . ' to ' . @$message->method . ' transfer is not accepted', 'Warning');
                    return redirect()->back();
                }
            }
            // Validation Part End

            $from_payment = SmPaymentMethhod::where('school_id', Auth::user()->school_id)->findOrFail($request->from_payment_method);

            if ($from_payment->method == 'Bank') {
                $balance = SmBankAccount::where('school_id', Auth::user()->school_id)->findOrFail($request->from_bank_name)->current_balance;

                if ($balance > $request->amount && $balance != 0) {
                    $transfer = new SmAmountTransfer();
                    $transfer->amount = $request->amount;
                    $transfer->purpose = $request->purpose;
                    $transfer->from_payment_method = $request->from_payment_method;
                    $transfer->from_bank_name = $request->from_bank_name;
                    $transfer->to_payment_method = $request->to_payment_method;
                    $transfer->to_bank_name = $request->to_bank_name;
                    $transfer->transfer_date = Carbon::now();
                    $transfer->school_id = Auth::user()->school_id;
                    $transfer->academic_id = getAcademicId();
                    $transfer->save();

                    $add_expense = new SmAddExpense();
                    $add_expense->name = "Fund Transfer";
                    $add_expense->date = Carbon::now();
                    $add_expense->amount = $request->amount;
                    $add_expense->payment_method_id = $request->from_payment_method;
                    $add_expense->account_id = $request->from_bank_name;
                    $add_expense->school_id = Auth::user()->school_id;
                    $add_expense->academic_id = getAcademicId();
                    $add_expense->save();

                    $add_income = new SmAddIncome();
                    $add_income->name = "Fund Transfer";
                    $add_income->date = Carbon::now();
                    $add_income->amount = $request->amount;
                    $add_income->payment_method_id = $request->to_payment_method;
                    if ($request->to_bank_name) {
                        $add_income->account_id = $request->to_bank_name;
                    }
                    $add_income->account_id = $request->to_bank_name;
                    $add_income->school_id = Auth::user()->school_id;
                    $add_income->academic_id = getAcademicId();
                    $add_income->save();


                    $bank_id = SmBankAccount::where('id', $request->from_bank_name)
                        ->where('school_id', Auth::user()->school_id)
                        ->first();
                    $bank_expense = $bank_id->current_balance - $request->amount;

                    $bank_statement = new SmBankStatement();
                    $bank_statement->amount = $request->amount;
                    $bank_statement->after_balance = $bank_expense;
                    $bank_statement->type = 0;
                    $bank_statement->details = "Fund Transfer";
                    $bank_statement->item_receive_id = $transfer->id;
                    $bank_statement->payment_date = Carbon::now();
                    $bank_statement->bank_id = $request->from_bank_name;
                    $bank_statement->school_id = Auth::user()->school_id;
                    $bank_statement->payment_method = $request->from_payment_method;
                    $bank_statement->save();


                    $new_balance = SmBankAccount::find($request->from_bank_name);
                    $new_balance->current_balance = $bank_expense;
                    $new_balance->update();

                    if ($request->to_bank_name) {
                        $bank_id = SmBankAccount::where('id', $request->to_bank_name)->first();
                        $bank_income = $bank_id->current_balance + $request->amount;

                        $bank_statement = new SmBankStatement();
                        $bank_statement->amount = $request->amount;
                        $bank_statement->after_balance = $bank_income;
                        $bank_statement->type = 1;
                        $bank_statement->details = "Fund Transfer";
                        $bank_statement->item_receive_id = $transfer->id;
                        $bank_statement->payment_date = Carbon::now();
                        $bank_statement->bank_id = $request->to_bank_name;
                        $bank_statement->school_id = Auth::user()->school_id;
                        $bank_statement->payment_method = $request->to_payment_method;
                        $bank_statement->save();

                        $new_balance = SmBankAccount::find($request->to_bank_name);
                        $new_balance->current_balance = $bank_income;
                        $new_balance->update();
                    }
                    Toastr::success('Operation successful', 'Success');
                    return redirect('fund-transfer');
                } else {
                    Toastr::error('Operation Failed1', 'Failed');
                    return redirect()->back();
                }
            } else {
                $income = SmAddIncome::where('payment_method_id', $request->from_payment_method)
                    ->where('school_id', Auth::user()->school_id)
                    ->sum('amount');

                $expense = SmAddExpense::where('payment_method_id', $request->from_payment_method)
                    ->where('school_id', Auth::user()->school_id)
                    ->sum('amount');

                $balance = $income - $expense;

                if ($income > $expense && $balance != 0 && $balance >= $request->amount) {
                    $transfer = new SmAmountTransfer();
                    $transfer->amount = $request->amount;
                    $transfer->purpose = $request->purpose;
                    $transfer->from_payment_method = $request->from_payment_method;
                    $transfer->to_payment_method = $request->to_payment_method;
                    if ($request->to_bank_name) {
                        $transfer->to_bank_name = $request->to_bank_name;
                    }
                    $transfer->transfer_date = Carbon::now();
                    $transfer->school_id = Auth::user()->school_id;
                    $transfer->academic_id = getAcademicId();
                    $transfer->save();

                    $add_expense = new SmAddExpense();
                    $add_expense->name = "Fund Transfer";
                    $add_expense->date = Carbon::now();
                    $add_expense->amount = $request->amount;
                    $add_expense->payment_method_id = $request->from_payment_method;
                    if ($request->to_bank_name) {
                        $add_expense->account_id = $request->to_bank_name;
                    }
                    $add_expense->school_id = Auth::user()->school_id;
                    $add_expense->academic_id = getAcademicId();
                    $add_expense->save();

                    $add_income = new SmAddIncome();
                    $add_income->name = "Fund Transfer";
                    $add_income->date = Carbon::now();
                    $add_income->amount = $request->amount;
                    $add_income->payment_method_id = $request->to_payment_method;
                    if ($request->to_bank_name) {
                        $add_expense->account_id = $request->to_bank_name;
                    }
                    $add_income->school_id = Auth::user()->school_id;
                    $add_income->academic_id = getAcademicId();
                    $add_income->save();

                    if ($request->to_bank_name) {

                        $bank_id = SmBankAccount::where('id', $request->to_bank_name)
                            ->where('school_id', Auth::user()->school_id)
                            ->first();

                        $bank_income = $bank_id->current_balance + $request->amount;

                        $bank_statement = new SmBankStatement();
                        $bank_statement->amount = $request->amount;
                        $bank_statement->after_balance = $bank_income;
                        $bank_statement->type = 1;
                        $bank_statement->details = "Fund Transfer";
                        $bank_statement->item_receive_id = $transfer->id;
                        $bank_statement->payment_date = Carbon::now();
                        $bank_statement->bank_id = $request->to_bank_name;
                        $bank_statement->school_id = Auth::user()->school_id;
                        $bank_statement->payment_method = $request->to_payment_method;
                        $bank_statement->save();

                        $new_balance = SmBankAccount::find($request->to_bank_name);
                        $new_balance->current_balance = $bank_income;
                        $new_balance->update();
                    }
                    Toastr::success('Operation successful', 'Success');
                    return redirect('fund-transfer');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
                Toastr::success('Operation successful', 'Success');
                return redirect('fund-transfer');
            }
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
