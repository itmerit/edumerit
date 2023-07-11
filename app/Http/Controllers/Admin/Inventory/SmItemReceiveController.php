<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\SmItem;
use App\SmItemSell;
use App\SmSupplier;
use App\SmItemStore;
use App\SmAddExpense;
use App\SmBankAccount;
use App\SmItemReceive;
use App\SmBankStatement;
use App\SmChartOfAccount;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use App\SmInventoryPayment;
use App\SmItemReceiveChild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Inventory\SmItemReceiveRequest;

class SmItemReceiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function itemReceive()
    {
        try {
            $account_id = SmBankAccount::get();
            $expense_head = SmChartOfAccount::where('type', 'E')->get();
            $suppliers = SmSupplier::get();
            $itemStores = SmItemStore::where('school_id', Auth::user()->school_id)->get();
            $items = SmItem::with('category')->get();
            $paymentMethhods = SmPaymentMethhod::get();
            return view('backEnd.inventory.itemReceive', compact('suppliers', 'itemStores', 'items', 'paymentMethhods','account_id', 'expense_head'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getReceiveItem()
    {
        try {
            $searchData = SmItem::where('school_id', Auth::user()->school_id)->get();
            if (!empty($searchData)) {
                return json_encode($searchData);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function saveItemReceiveData(SmItemReceiveRequest $request)
    {
       //   uest->all());
       // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        try {
            $total_paid = '';
            if (empty($request->totalPaidValue)) {
                $total_paid = $request->totalPaid;
            } else {
                $total_paid = $request->totalPaidValue;
            }
            $subTotalValue = round($request->subTotalValue);
            $totalDueValue = round($request->totalDueValue);
            $paid_status = '';
            if ($totalDueValue == 0) {
                $paid_status = 'P';
            } elseif ($subTotalValue == $totalDueValue) {
                $paid_status = 'U';
            } else {
                $paid_status = 'PP';
            }
            $itemReceives = new SmItemReceive();
            $itemReceives->supplier_id = $request->supplier_id;
            $itemReceives->store_id = $request->store_id;
            $itemReceives->reference_no = $request->reference_no;
            $itemReceives->receive_date = date('Y-m-d', strtotime($request->receive_date));
            $itemReceives->grand_total = $request->subTotalValue;
            $itemReceives->total_quantity = $request->subTotalQuantityValue;
            $itemReceives->total_paid = $total_paid;
            $itemReceives->paid_status = $paid_status;
            $itemReceives->total_due = $request->totalDueValue;
            $itemReceives->account_id = $request->bank_id;
            $itemReceives->expense_head_id = $request->expense_head_id;
            $itemReceives->payment_method = $request->payment_method;
            $itemReceives->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $itemReceives->un_academic_id = getAcademicId();
            }else{
                $itemReceives->academic_id = getAcademicId();
            }
            $results = $itemReceives->save();
            $itemReceives->toArray();

            $add_expense = new SmAddExpense();
            $add_expense->name = 'Item Receive';
            $add_expense->date = date('Y-m-d', strtotime($request->receive_date));
            $add_expense->amount = $total_paid;
            $add_expense->item_receive_id = $itemReceives->id;
            $add_expense->active_status = 1;
            $add_expense->expense_head_id = $request->expense_head_id;
            $add_expense->account_id = $request->bank_id;
            $add_expense->payment_method_id = $request->payment_method;
            $add_expense->created_by = Auth()->user()->id;
            $add_expense->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $add_expense->un_academic_id = getAcademicId();
            }else{
                $add_expense->academic_id = getAcademicId();
            }
            $add_expense->save();

            if(paymentMethodName($request->payment_method)){
                $bank=SmBankAccount::where('id',$request->bank_id)
                ->where('school_id',Auth::user()->school_id)
                ->first();
                $after_balance= $bank->current_balance - $total_paid;

                $bank_statement= new SmBankStatement();
                $bank_statement->amount= $total_paid;
                $bank_statement->after_balance= $after_balance;
                $bank_statement->type= 0;
                $bank_statement->details= "Item Receive Payment";
                $bank_statement->item_receive_id= $itemReceives->id;
                $bank_statement->payment_date= date('Y-m-d', strtotime($request->receive_date));
                $bank_statement->bank_id= $request->bank_id;
                $bank_statement->school_id=Auth::user()->school_id;
                $bank_statement->payment_method= $request->payment_method;
                $bank_statement->save();


                $current_balance= SmBankAccount::find($request->bank_id);
                $current_balance->current_balance=$after_balance;
                $current_balance->update();
            }

            if ($results) {
                $item_ids = count($request->item_id);
                for ($i = 0; $i < $item_ids; $i++) {
                    if (!empty($request->item_id[$i])) {
                        $itemReceivedChild = new SmItemReceiveChild;
                        $itemReceivedChild->item_receive_id = $itemReceives->id;
                        $itemReceivedChild->item_id = $request->item_id[$i];
                        $itemReceivedChild->unit_price = $request->unit_price[$i];
                        $itemReceivedChild->quantity = $request->quantity[$i];
                        $itemReceivedChild->sub_total = $request->totalValue[$i];
                        $itemReceivedChild->created_by = Auth()->user()->id;
                          $itemReceivedChild->academic_id = getAcademicId();
                        $itemReceivedChild->school_id = Auth::user()->school_id;
                        $result = $itemReceivedChild->save();

                        if ($result) {
                            $items = SmItem::find($request->item_id[$i]);
                            $items->total_in_stock = $items->total_in_stock + $request->quantity[$i];
                            $results = $items->update();
                        }
                    }
                }

            } 
            Toastr::success('Operation successful', 'Success');
            return redirect('item-receive-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function itemReceiveList()
    {
        try {
            $allItemReceiveLists = SmItemReceive::with('suppliers','paymentMethodName','bankName')
                ->get();

            return view('backEnd.inventory.itemReceiveList', compact('allItemReceiveLists'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editItemReceive(Request $request, $id)
    {
        try {
            $expense_head = SmChartOfAccount::where('type', 'E')->get();

            $account_id   = SmBankAccount::get();

            $editData     = SmItemReceive::find($id);

            $editDataChildren = SmItemReceiveChild::with('items')->where('item_receive_id', $id)            
                                 ->get();

            $suppliers    = SmSupplier::get();

            $itemStores   = SmItemStore::get();

            $items        = SmItem::get();

            $paymentMethhods = SmPaymentMethhod::where('id','!=',3)
                            ->get();

            return view('backEnd.inventory.editItemReceive', compact('editData', 'editDataChildren', 'suppliers', 'itemStores', 'items', 'paymentMethhods', 'expense_head','account_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateItemReceiveData(SmItemReceiveRequest $request, $id)
    {
       // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        try {
            $total_paid = '';
            if (empty($request->totalPaidValue)) {
                $total_paid = $request->totalPaid;
            } else {
                $total_paid = $request->totalPaidValue;
            }
            $subTotalValue = round($request->subTotalValue);
            $totalDueValue = round($request->totalDueValue);
            $paid_status = '';
            if ($totalDueValue == 0) {
                $paid_status = 'P';
            } elseif ($subTotalValue == $totalDueValue) {
                $paid_status = 'U';
            } else {
                $paid_status = 'PP';
            }
            if(paymentMethodName($request->payment_method)){
                $current_balance_subtraction = SmItemReceive::find($id);
                $item_value=$current_balance_subtraction->total_paid;

                $bank_value= SmBankAccount::find($request->bank_id);
                $current_bank_value=$bank_value->current_balance;

                $current_balance= SmBankAccount::find($request->bank_id);
                $current_balance->current_balance=$current_bank_value+$item_value;
                $current_balance->update();
            }

            $itemReceives = SmItemReceive::find($id);
            $itemReceives->supplier_id = $request->supplier_id;
            $itemReceives->store_id = $request->store_id;
            $itemReceives->reference_no = $request->reference_no;
            $itemReceives->receive_date = date('Y-m-d', strtotime($request->receive_date));
            $itemReceives->grand_total = $request->subTotalValue;
            $itemReceives->total_quantity = $request->subTotalQuantityValue;
            $itemReceives->total_paid = $total_paid;
            $itemReceives->paid_status = $paid_status;
            $itemReceives->expense_head_id = $request->expense_head_id;
            $itemReceives->total_due = $request->totalDueValue;
            $itemReceives->payment_method = $request->payment_method;
            $results = $itemReceives->update();

            SmAddExpense::where('item_receive_id', $itemReceives->id)->delete();

            $add_expense = new SmAddExpense();
            $add_expense->name = 'Item Receive';
            $add_expense->date = date('Y-m-d', strtotime($request->receive_date));
            $add_expense->amount = $total_paid;
            $add_expense->item_receive_id = $itemReceives->id;
            $add_expense->active_status = 1;
            $add_expense->expense_head_id = $request->expense_head_id;
            $add_expense->payment_method_id = $request->payment_method;
            $add_expense->created_by = Auth()->user()->id;
            $add_expense->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $add_expense->un_academic_id = getAcademicId();
            }else{
                $add_expense->academic_id = getAcademicId();
            }
            $add_expense->save();

            if(paymentMethodName($request->payment_method)){
                SmBankStatement::where('item_receive_id', $itemReceives->id)
                                    ->where('school_id',Auth::user()->school_id)
                                    ->delete();
                
                
                $bank=SmBankAccount::where('id',$request->bank_id)
                    ->where('school_id',Auth::user()->school_id)
                    ->first();
                $after_balance= $bank->current_balance - $total_paid;

                $bank_statement= new SmBankStatement();
                $bank_statement->amount= $total_paid;
                $bank_statement->after_balance= $after_balance;
                $bank_statement->type= 0;
                $bank_statement->details= "Item Receive Payment";
                $bank_statement->item_receive_id= $itemReceives->id;
                $bank_statement->payment_date= date('Y-m-d', strtotime($request->receive_date));
                $bank_statement->bank_id= $request->bank_id;
                $bank_statement->school_id= Auth::user()->school_id;
                $bank_statement->payment_method= $request->payment_method;
                $bank_statement->save();


                $current_balance= SmBankAccount::find($request->bank_id);
                $current_balance->current_balance=$after_balance;
                $current_balance->update();
            }

            if ($results) {
                $allItemReceiveChildren = SmItemReceiveChild::where('item_receive_id', $id)->where('school_id', Auth::user()->school_id)->get();
                foreach ($allItemReceiveChildren as $value) {
                    $items = SmItem::find($value->item_id);
                    $items->total_in_stock = $items->total_in_stock - $value->quantity;
                    $results = $items->update();
                }
            }

            $itemReceiveChildren = SmItemReceiveChild::where('item_receive_id', $id)->delete();

            if ($itemReceiveChildren) {
                $item_ids = count($request->item_id);
                for ($i = 0; $i < $item_ids; $i++) {
                    if (!empty($request->item_id[$i])) {
                        $itemReceivedChild = new SmItemReceiveChild;
                        $itemReceivedChild->item_receive_id = $id;
                        $itemReceivedChild->item_id = $request->item_id[$i];
                        $itemReceivedChild->unit_price = $request->unit_price[$i];
                        $itemReceivedChild->quantity = $request->quantity[$i];
                        $itemReceivedChild->sub_total = $request->totalValue[$i];
                        $itemReceivedChild->created_by = Auth()->user()->id;
                        $itemReceivedChild->school_id = Auth::user()->school_id;
                        if(!moduleStatusCheck('University')){
                            $itemReceivedChild->academic_id = getAcademicId();
                        }
                        $result = $itemReceivedChild->save();

                        if ($result) {
                            $items = SmItem::find($request->item_id[$i]);
                            $items->total_in_stock = $items->total_in_stock + $request->quantity[$i];
                            $results = $items->update();
                        }
                    }
                }

                Toastr::success('Operation successful', 'Success');
                return redirect('item-receive-list');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewItemReceive($id)
    {
        try {
            $general_setting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $viewData = SmItemReceive::find($id);
            $editDataChildren = SmItemReceiveChild::where('item_receive_id', $id)->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.inventory.viewItemReceive', compact('viewData', 'editDataChildren', 'general_setting'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function itemReceivePayment($id)
    {
        try {
            $paymentDue = SmItemReceive::select('total_due')->where('id', $id)->first();

            $editData = SmItemReceive::find($id);

            $paymentMethhods = SmPaymentMethhod::where('school_id', Auth::user()->school_id)
                ->where('active_status', 1)
                ->get();
            $account_id = SmBankAccount::where('school_id', Auth::user()->school_id)
                        ->get();

            $expense_head = SmChartOfAccount::where('active_status', '=', 1)
                ->where('school_id', Auth::user()->school_id)
                ->where('type', 'E')
                ->get();

            return view('backEnd.inventory.itemReceivePayment', compact('paymentDue', 'paymentMethhods', 'id', 'expense_head','editData','account_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function saveItemReceivePayment(Request $request)
    {
      //  DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        try {
            $payments = new SmInventoryPayment();
            $payments->item_receive_sell_id = $request->item_receive_id;
            $payments->payment_date = date('Y-m-d', strtotime($request->payment_date));
            $payments->reference_no = $request->reference_no;
            $payments->amount = $request->amount;
            $payments->payment_method = $request->payment_method;
            $payments->notes = $request->notes;
            $payments->payment_type = 'R';
            $payments->created_by = Auth()->user()->id;
            $payments->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $payments->un_academic_id = getAcademicId();
            }else{
                $payments->academic_id = getAcademicId();
            }
            $result = $payments->save();

            $itemPaymentDue = SmItemReceive::find($request->item_receive_id);
            if (isset($itemPaymentDue)) {
                $total_due = $itemPaymentDue->total_due;
                $total_paid = $itemPaymentDue->total_paid;
                $updated_total_due = $total_due - $request->amount;
                $updated_total_paid = $total_paid + $request->amount;
                $itemPaymentDue->total_due = $updated_total_due;
                $itemPaymentDue->total_paid = $updated_total_paid;
                if(moduleStatusCheck('University')){
                    $itemPaymentDue->un_academic_id = getAcademicId();
                }else{
                    $itemPaymentDue->academic_id = getAcademicId();
                }
                $result = $itemPaymentDue->update();

                $add_expense = new SmAddExpense();
                $add_expense->name = 'Item Receive';
                $add_expense->date = date('Y-m-d', strtotime($request->payment_date));
                $add_expense->amount = $request->amount;
                $add_expense->item_receive_id = $request->item_receive_id;
                $add_expense->active_status = 1;
                $add_expense->expense_head_id = $request->expense_head_id;
                $add_expense->inventory_id = $payments->id;
                $add_expense->payment_method_id = $request->payment_method;
                $add_expense->created_by = Auth()->user()->id;
                $add_expense->school_id = Auth::user()->school_id;
                if(moduleStatusCheck('University')){
                    $add_expense->un_academic_id = getAcademicId();
                }else{
                    $add_expense->academic_id = getAcademicId();
                }
                $add_expense->save();

                if(paymentMethodName($request->payment_method)){
                    $bank=SmBankAccount::where('id',$request->bank_id)
                    ->where('school_id',Auth::user()->school_id)
                    ->first();
                    $after_balance= $bank->current_balance - $request->amount;
    
                    $bank_statement= new SmBankStatement();
                    $bank_statement->amount= $request->amount;
                    $bank_statement->after_balance= $after_balance;
                    $bank_statement->type= 0;
                    $bank_statement->details= "Item Receive Payment";
                    $bank_statement->item_receive_id= $request->item_receive_id;
                    $bank_statement->item_receive_bank_statement_id = $payments->id;
                    $bank_statement->payment_date= date('Y-m-d', strtotime($request->payment_date));
                    $bank_statement->bank_id= $request->bank_id;
                    $bank_statement->school_id= Auth::user()->school_id;
                    $bank_statement->payment_method= $request->payment_method;
                    $bank_statement->save();

                    $current_balance= SmBankAccount::find($request->bank_id);
                    $current_balance->current_balance=$after_balance;
                    $current_balance->update();
                }

            }

            // check if full paid
            $itemReceives = SmItemReceive::find($request->item_receive_id);
            if ($itemReceives->total_due == 0) {
                $itemReceives->paid_status = 'P';
            }

            // check if Partial paid
            if ($itemReceives->grand_total > $itemReceives->total_due && $itemReceives->total_due > 0) {
                $itemReceives->paid_status = 'PP';
            }

            $results = $itemReceives->update();

            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewReceivePayments($id)
    {

        try {
            $payments = SmInventoryPayment::where('item_receive_sell_id', $id)->where('payment_type', 'R')->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.inventory.viewReceivePayments', compact('payments', 'id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteReceivePayment()
    {
        try {
            $receive_payment_id = $_POST['receive_payment_id'];
            $paymentHistory = SmInventoryPayment::find($receive_payment_id);
            $item_receive_sell_id = $paymentHistory->item_receive_sell_id;
            $amount = $paymentHistory->amount;

            $itemReceivesData = SmItemReceive::find($item_receive_sell_id);
            $itemReceivesData->total_due = $itemReceivesData->total_due + $amount;
            $itemReceivesData->total_paid = $itemReceivesData->total_paid - $amount;

            if(paymentMethodName($itemReceivesData->payment_method)){
                $bank=SmBankAccount::where('id',$itemReceivesData->account_id)
                ->where('school_id',Auth::user()->school_id)
                ->first();
                $after_balance= $bank->current_balance + $amount;

                $current_balance= SmBankAccount::find($itemReceivesData->account_id);
                $current_balance->current_balance=$after_balance;
                $current_balance->update();

                $delete_balance = SmBankStatement::where('item_receive_id',$itemReceivesData->id)
                                ->where('item_receive_bank_statement_id',$paymentHistory->id)
                                ->where('amount',$amount)
                                ->delete();
            }

            $delete_expense=SmAddExpense::where('inventory_id',$paymentHistory->id)->delete();

            // check if total due is greater than 0
            if (($itemReceivesData->total_due + $amount) > 0) {
                $itemReceivesData->paid_status = 'PP';
            }
            // check if total due is equal to 0
            if (($itemReceivesData->total_due + $amount) == 0) {
                $itemReceivesData->paid_status = 'P';
            }
            $itemReceivesData->update();
            $result = SmInventoryPayment::destroy($receive_payment_id);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteItemReceiveView($id)
    {
        try {
            $title = "Are you sure to detete this Receive item?";
            $url = url('delete-item-receive/' . $id);
            return view('backEnd.modal.delete', compact('id', 'title', 'url'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteItemSaleView($id)
    {
        try {
            $title = "Are you sure to detete this Sale item?";
            $url = url('delete-item-sale/' . $id);
            return view('backEnd.modal.delete', compact('id', 'title', 'url'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteItemReceive($id)
    {
        try {
            $itemReceivedChilds = SmItemReceiveChild::where('item_receive_id', $id)
                                ->where('school_id', Auth::user()->school_id)
                                ->get();
            foreach ($itemReceivedChilds as $value) {
                $items = SmItem::where('id', $value->item_id)->where('school_id', Auth::user()->school_id)->first();
                $items->total_in_stock = $items->total_in_stock - $value->quantity;
                $results = $items->update();
                $iReceChi = SmItemReceiveChild::where('id', $value->id)->where('school_id', Auth::user()->school_id)->delete();
            }
            $result = SmItemReceive::where('id', $id)->where('school_id', Auth::user()->school_id)->delete();
            $delete_expense=SmAddExpense::where('item_receive_id',$id)->where('school_id', Auth::user()->school_id)->delete();
            
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteItemSale($id)
    {
        try {
            $tables = \App\tableList::getTableList('item_sell_id', $id);
            try {
                $result = SmItemSell::destroy($id);
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {

                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error('This item already used', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function cancelItemReceiveView($id)
    {

        try {
            return view('backEnd.inventory.cancelItemReceiveView', compact('id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function cancelItemReceive($id)
    {
        try {
            $itemReceives = SmItemReceive::find($id);
            $itemReceives->paid_status = 'R';
            $results = $itemReceives->update();

            $itemReceives->expnese_head_id;
            $refund = SmAddExpense::where('item_receive_id',$itemReceives->id)
                        ->where('school_id', Auth::user()->school_id)
                        ->delete();

            if(paymentMethodName($itemReceives->payment_method)){
                $reset_balance = SmBankStatement::where('item_receive_id',$itemReceives->id)
                                ->where('school_id',Auth::user()->school_id)
                                ->sum('amount');

                    $bank=SmBankAccount::where('id',$itemReceives->account_id)
                    ->where('school_id',Auth::user()->school_id)
                    ->first();
                    $after_balance= $bank->current_balance + $reset_balance;

                    $current_balance= SmBankAccount::find($itemReceives->account_id);
                    $current_balance->current_balance=$after_balance;
                    $current_balance->update();

                    $delete_balance = SmBankStatement::where('item_receive_id',$itemReceives->id)
                                        ->where('school_id',Auth::user()->school_id)
                                        ->delete();
            }
            if ($results) {
                $itemReceiveChild = SmItemReceiveChild::where('item_receive_id', $id)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                if (!empty($itemReceiveChild)) {
                    foreach ($itemReceiveChild as $value) {
                        $items = SmItem::find($value->item_id);
                        $items->total_in_stock = $items->total_in_stock - $value->quantity;
                        $result = $items->update();
                    }
                }
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
