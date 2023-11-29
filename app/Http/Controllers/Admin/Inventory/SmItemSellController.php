<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\SmItem;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\SmItemSell;
use App\SmSupplier;
use App\SmAddIncome;
use App\SmItemIssue;
use App\ApiBaseMethod;
use App\SmBankAccount;
use App\SmItemReceive;
use App\SmItemCategory;
use App\SmBankStatement;
use App\SmItemSellChild;
use App\SmChartOfAccount;
use App\SmPaymentMethhod;
use App\SmInventoryPayment;
use App\SmItemReceiveChild;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\InfixRole;
use App\Http\Requests\Admin\Inventory\SmItemSellRequest;
use App\Http\Requests\Admin\Inventory\SmItemIssueRequest;

class SmItemSellController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function itemSell(Request $request)
    {
        try {
            $sell_heads = SmChartOfAccount::where('type', 'I')
                            ->get();
                
            $account_id = SmBankAccount::get();

            $suppliers  = SmSupplier::get();
            $items      = SmItem::get();
            $roles      =  InfixRole::when((generalSetting()->with_guardian !=1), function ($query) {
                            $query->where('id', '!=', 3);
                                })->where(function ($q) {
                                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
                                })->get();
         
            $classes    = SmClass::get();
            $paymentMethhods = SmPaymentMethhod::get();
            return view('backEnd.inventory.itemSell', compact('suppliers', 'items', 'paymentMethhods', 'roles','classes', 'sell_heads','account_id'));
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


    public function saveItemSellData(SmItemSellRequest $request)
    {
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
            $student_staff_id = '';
            if (!empty($request->student)) {
                $student_staff_id = $request->student;
            }
            if (!empty($request->staff_id)) {
                $student_staff_id = $request->staff_id;
            }
            $itemSells = new SmItemSell();
            $itemSells->role_id = $request->role_id;
            $itemSells->student_staff_id = $student_staff_id;
            $itemSells->reference_no = $request->reference_no;
            $itemSells->sell_date = date('Y-m-d', strtotime($request->sell_date));
            if (@$request->subTotalValue) {
                $itemSells->grand_total = $request->subTotalValue;
            }
            if (@$request->subTotalQuantityValue) {
                $itemSells->total_quantity = $request->subTotalQuantityValue;
            }
            $itemSells->total_paid = $total_paid;
            $itemSells->paid_status = $paid_status;
            $itemSells->total_due = $request->totalDueValue;
            $itemSells->account_id = $request->bank_id;
            $itemSells->income_head_id = $request->income_head_id;
            $itemSells->payment_method = $request->payment_method;
            $itemSells->description = $request->description;
            $itemSells->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $itemSells->un_academic_id = getAcademicId();
            }else{
                $itemSells->academic_id = getAcademicId();
            }
            $results = $itemSells->save();
            $itemSells->toArray();

            $add_income = new SmAddIncome();
            $add_income->name = 'Item Sell';
            $add_income->date = date('Y-m-d', strtotime($request->sell_date));
            $add_income->amount = $total_paid;
            $add_income->item_sell_id = $itemSells->id;
            $add_income->active_status = 1;
            $add_income->income_head_id = $request->income_head_id;
            $add_income->account_id = $request->bank_id;
            $add_income->payment_method_id = $request->payment_method;
            $add_income->created_by = Auth()->user()->id;
            $add_income->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $add_income->un_academic_id = getAcademicId();
            }else{
                $add_income->academic_id = getAcademicId();
            }
            $add_income->save();


            if(paymentMethodName($request->payment_method)){
                $bank=SmBankAccount::where('id',$request->bank_id)
                ->where('school_id',Auth::user()->school_id)
                ->first();
                $after_balance= $bank->current_balance + $total_paid;

                $bank_statement= new SmBankStatement();
                $bank_statement->amount= $total_paid;
                $bank_statement->after_balance= $after_balance;
                $bank_statement->type= 1;
                $bank_statement->details= "Item Sell Payment";
                $bank_statement->item_sell_id= $itemSells->id;
                $bank_statement->payment_date= date('Y-m-d h:i:sa', strtotime($request->sell_date));
                $bank_statement->bank_id= $request->bank_id;
                $bank_statement->school_id= Auth::user()->school_id;
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
                        $itemSellChild = new SmItemSellChild();
                        $itemSellChild->item_sell_id = $itemSells->id;
                        $itemSellChild->item_id = $request->item_id[$i];
                        $itemSellChild->sell_price = $request->unit_price[$i];
                        $itemSellChild->quantity = $request->quantity[$i];
                        $itemSellChild->sub_total = $request->totalValue[$i];
                        $itemSellChild->created_by = Auth()->user()->id;
                        $itemSellChild->school_id = Auth::user()->school_id;
                        if(!moduleStatusCheck('University')){
                            $itemSellChild->academic_id = getAcademicId();
                        }
                        $result = $itemSellChild->save();

                        if ($result) {
                            $items = SmItem::find($request->item_id[$i]);
                            $items->total_in_stock = $items->total_in_stock - $request->quantity[$i];
                            if(moduleStatusCheck('University')){
                                $items->un_academic_id = getAcademicId();
                            }else{
                                $items->academic_id = getAcademicId();
                            }
                            $results = $items->update();
                        }
                    }
                }
            }

            Toastr::success('Operation successful', 'Success');
            return redirect('item-sell-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function checkProductQuantity()
    {
        try {
            $product_id = $_POST['product_id'];
            $product_quantity = SmItem::select('total_in_stock')->where('id', $product_id)->first();
            return $product_quantity->total_in_stock;
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function itemSellList()
    {
        try {
            $allItemSellLists = SmItemSell::where('active_status', '=', 1)
                                ->where('school_id', Auth::user()->school_id)
                                ->orderby('id','DESC')
                                ->get();

            return view('backEnd.inventory.itemSellList', compact('allItemSellLists'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewItemSell($id)
    {
        try {
            $general_setting = generalSetting();         
            $viewData = SmItemSell::find($id);            
            $editDataChildren = SmItemSellChild::with('items')->where('item_sell_id', $id)->get();
            return view('backEnd.inventory.viewItemSell', compact('viewData', 'editDataChildren', 'general_setting'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewItemSellPrint($id)
    {
    
        try {
            $viewData = SmItemSell::find($id);
            $editDataChildren = SmItemSellChild::with('items')->where('item_sell_id', $id)->get();
            $pdf = PDF::loadView('backEnd.inventory.item_sell_print', ['viewData' => $viewData, 'editDataChildren' => $editDataChildren]);
            return $pdf->stream(date('d-m-Y') . '-item-sell-paid.pdf');
            return view('backEnd.inventory.item_sell_print', compact('viewData', 'editDataChildren'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editItemSell(Request $request, $id)
    {
        try {
            $editData = SmItemSell::find($id);
            
            $roles = InfixRole::where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();

            $sell_heads = SmChartOfAccount::where('type', 'I')
                        ->get();

            $account_id = SmBankAccount::get();

            $studentClassSection = '';
            $allStudentsSameClassSection = '';
            $staffsByRole = '';
            if ($editData->role_id == 2) {
                $studentClassSection = SmStudent::where('id', $editData->student_staff_id)->first();
                $student_ids = StudentRecord::when($studentClassSection->defaultClass, function($q) use($studentClassSection){
                    $q->where('class_id', $studentClassSection->defaultClass->class_id)->where('section_id', $studentClassSection->defaultClass->section_id);
                })->pluck('student_id')->unique()->toArray();
                $allStudentsSameClassSection = SmStudent::whereIn('id', $student_ids)->get();
            } elseif ($editData->role_id == 3) {
                $staffsByRole = SmParent::where('active_status', 1)                  
                                        ->get();
            } else {
                $staffsByRole = SmStaff::where('role_id', $editData->role_id)
                   
                    ->get();
            }
            $editDataChildren = SmItemSellChild::where('item_sell_id', $id)
              
                ->get();

         
            $items = SmItem::get();
            $classes = SmClass::get();
            $sections = SmSection::get();

            $paymentMethhods = SmPaymentMethhod::get();


            return view('backEnd.inventory.editItemSell', compact('editData', 'editDataChildren', 'roles', 'items', 'paymentMethhods', 'classes', 'sections', 'studentClassSection', 'allStudentsSameClassSection', 'staffsByRole','sell_heads','account_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function UpdateItemSellData(SmItemSellRequest $request)
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

            $student_staff_id = '';
            if ($request->role_id == 2) {
                $student_staff_id = $request->student;
            } elseif ($request->role_id != 2) {
                $student_staff_id = $request->staff_id;
            }

            if(paymentMethodName($request->payment_method)){
                $current_balance_addition = SmItemSell::find($request->id);
                $item_value=$current_balance_addition->total_paid;

                $bank_value= SmBankAccount::find($request->bank_id);
                $current_bank_value=$bank_value->current_balance;

                $current_balance= SmBankAccount::find($request->bank_id);
                $current_balance->current_balance=$current_bank_value - $item_value;
                $current_balance->update();
            }


            $itemSells = SmItemSell::find($request->id);
            $itemSells->role_id = $request->role_id;
            $itemSells->student_staff_id = $student_staff_id;
            $itemSells->reference_no = $request->reference_no;
            $itemSells->sell_date = date('Y-m-d', strtotime($request->sell_date));
            $itemSells->grand_total = $request->subTotalValue;
            $itemSells->total_quantity = $request->subTotalQuantityValue;
            $itemSells->total_paid = $total_paid;
            $itemSells->income_head_id = $request->income_head_id;
            $itemSells->paid_status = $paid_status;
            $itemSells->total_due = $request->totalDueValue;
            $itemSells->payment_method = $request->payment_method;
            $itemSells->description = $request->description;
            $results = $itemSells->save();
            $itemSells->toArray();

            SmAddIncome::where('item_sell_id', $itemSells->id)->delete();
            
            $add_income = new SmAddIncome();
            $add_income->name = 'Item Sell';
            $add_income->date = date('Y-m-d', strtotime($request->sell_date));
            $add_income->amount = $total_paid;
            $add_income->item_sell_id = $itemSells->id;
            $add_income->active_status = 1;
            $add_income->income_head_id = $request->income_head_id;
            $add_income->payment_method_id = $request->payment_method;
            $add_income->created_by = Auth()->user()->id;
            $add_income->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $add_income->un_academic_id = getAcademicId();
            }else{
                $add_income->academic_id = getAcademicId();
            }
            $add_income->save();

            if(paymentMethodName($request->payment_method)){
               SmBankStatement::where('item_sell_id', $itemSells->id)
                                    ->where('school_id',Auth::user()->school_id)
                                    ->delete();
                
                
                $bank=SmBankAccount::where('id',$request->bank_id)
                ->where('school_id',Auth::user()->school_id)
                ->first();
                $after_balance= $bank->current_balance + $total_paid;

                $bank_statement= new SmBankStatement();
                $bank_statement->amount= $total_paid;
                $bank_statement->after_balance= $after_balance;
                $bank_statement->type= 1;
                $bank_statement->details= "Item Sell Payment";
                $bank_statement->item_sell_id= $itemSells->id;
                $bank_statement->payment_date= date('Y-m-d', strtotime($request->sell_date));
                $bank_statement->bank_id= $request->bank_id;
                $bank_statement->school_id= Auth::user()->school_id;
                $bank_statement->payment_method= $request->payment_method;
                $bank_statement->save();


                $current_balance= SmBankAccount::find($request->bank_id);
                $current_balance->current_balance=$after_balance;
                $current_balance->update();
            }
            

            if ($results) {
                SmItemSellChild::where('item_sell_id', $itemSells->id)->delete();
                $item_ids = count($request->item_id);
                for ($i = 0; $i < $item_ids; $i++) {
                    if (!empty($request->item_id[$i])) {
                        $itemSellChild = new SmItemSellChild();
                        $itemSellChild->item_sell_id = $itemSells->id;
                        $itemSellChild->item_id = $request->item_id[$i];
                        $itemSellChild->sell_price = $request->unit_price[$i];
                        $itemSellChild->quantity = $request->quantity[$i];
                        $itemSellChild->sub_total = $request->totalValue[$i];
                        $itemSellChild->created_by = Auth()->user()->id;
                        $itemSellChild->school_id = Auth::user()->school_id;
                        if(!moduleStatusCheck('University')){
                            $itemSellChild->academic_id = getAcademicId();
                        }
                        $result = $itemSellChild->save();

                        if ($result) {
                            $items = SmItem::find($request->item_id[$i]);
                            $items->total_in_stock = $items->total_in_stock - $request->quantity[$i];
                            if(moduleStatusCheck('University')){
                                $items->un_academic_id = getAcademicId();
                            }else{
                                $items->academic_id = getAcademicId();
                            }
                            $results = $items->update();
                        }
                    }
                }

            } 
            Toastr::success('Operation successful', 'Success');
            return redirect('item-sell-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateItemReceiveData(Request $request, $id)
    {
       // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $request->validate([
            'supplier_id' => "required",
            'store_id' => "required",

        ]);
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

            $itemReceives = SmItemReceive::find($id);
            $itemReceives->supplier_id = $request->supplier_id;
            $itemReceives->store_id = $request->store_id;
            $itemReceives->reference_no = $request->reference_no;
            $itemReceives->receive_date = date('Y-m-d', strtotime($request->receive_date));
            $itemReceives->grand_total = $request->subTotalValue;
            $itemReceives->total_quantity = $request->subTotalQuantityValue;
            $itemReceives->total_paid = $total_paid;
            $itemReceives->paid_status = $paid_status;
            $itemReceives->total_due = $request->totalDueValue;
            $itemReceives->payment_method = $request->payment_method;
            $results = $itemReceives->update();

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
                        $itemReceivedChild->academic_id = getAcademicId();
                        $result = $itemReceivedChild->save();

                        if ($result) {
                            $items = SmItem::find($request->item_id[$i]);
                            $items->total_in_stock = $items->total_in_stock + $request->quantity[$i];
                            $items->academic_id = getAcademicId();
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
            $viewData = SmItemReceive::where('id', $id)->where('school_id', Auth::user()->school_id)->first();
            $editDataChildren = SmItemReceiveChild::where('item_receive_id', $id)->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.inventory.viewItemReceive', compact('viewData', 'editDataChildren'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function itemSellPayment($id)
    {
        try {
            $editData = SmItemSell::find($id);

            $sell_heads = SmChartOfAccount::where('active_status', '=', 1)
                        ->where('school_id', Auth::user()->school_id)
                        ->where('type', 'I')
                        ->get();

            $paymentDue = SmItemSell::select('total_due')
                        ->where('id', $id)
                        ->where('school_id', Auth::user()->school_id)
                        ->first();

            $paymentMethhods = SmPaymentMethhod::where('school_id', Auth::user()->school_id)
                            ->where('active_status', 1)
                            ->get();

            return view('backEnd.inventory.itemSellPayment', compact('paymentDue', 'paymentMethhods', 'id','sell_heads','editData'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function saveItemSellPayment(Request $request)
    {
       // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        try {
            $payments = new SmInventoryPayment();
            $payments->item_receive_sell_id = $request->item_sell_id;
            $payments->payment_date = date('Y-m-d', strtotime($request->payment_date));
            $payments->reference_no = $request->reference_no;
            $payments->amount = $request->amount;
            $payments->payment_method = $request->payment_method;
            $payments->notes = $request->notes;
            $payments->payment_type = 'S';
            $payments->created_by = Auth()->user()->id;
            $payments->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $payments->un_academic_id = getAcademicId();
            }else{
                $payments->academic_id = getAcademicId();
            }
            $result = $payments->save();

            if (checkAdmin()) {
                $itemPaymentDue = SmItemSell::find($request->item_sell_id);
            } else {
                $itemPaymentDue = SmItemSell::where('id', $request->item_sell_id)->where('school_id', Auth::user()->school_id)->first();
            }
            if (isset($itemPaymentDue)) {
                $total_due = $itemPaymentDue->total_due;
                $total_paid = $itemPaymentDue->total_paid;
                $updated_total_due = $total_due - $request->amount;
                $updated_total_paid = $total_paid + $request->amount;
                $itemPaymentDue->total_due = $updated_total_due;
                $itemPaymentDue->total_paid = $updated_total_paid;
                $result = $itemPaymentDue->update();
            }

            // check if full paid
            $itemReceives = SmItemSell::find($request->item_sell_id);
            if ($itemReceives->total_due == 0) {
                $itemReceives->paid_status = 'P';
            }

            // check if Partial paid
            if ($itemReceives->grand_total > $itemReceives->total_due && $itemReceives->total_due > 0) {
                $itemReceives->paid_status = 'PP';
            }

            $results = $itemReceives->update();

            $add_income = new SmAddIncome();
            $add_income->name = 'Item Sell';
            $add_income->date = date('Y-m-d', strtotime($request->payment_date));
            $add_income->amount = $request->amount;
            $add_income->item_sell_id  = $request->item_sell_id;
            $add_income->active_status = 1;
            $add_income->income_head_id = $request->income_head_id;
            $add_income->inventory_id = $payments->id;
            $add_income->payment_method_id = $request->payment_method;
            $add_income->created_by = Auth()->user()->id;
            $add_income->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $add_income->un_academic_id = getAcademicId();
            }else{
                $add_income->academic_id = getAcademicId();
            }
            $add_income->save();


            if(paymentMethodName($request->payment_method)){
                $bank=SmBankAccount::where('id',$request->bank_id)
                ->where('school_id',Auth::user()->school_id)
                ->first();
                $after_balance= $bank->current_balance + $request->amount;

                $bank_statement= new SmBankStatement();
                $bank_statement->amount= $request->amount;
                $bank_statement->after_balance= $after_balance;
                $bank_statement->type= 1;
                $bank_statement->details= "Item Sell Payment";
                $bank_statement->item_sell_id= $request->item_sell_id;
                $bank_statement->item_sell_bank_statement_id = $payments->id;
                $bank_statement->payment_date= date('Y-m-d', strtotime($request->payment_date));
                $bank_statement->bank_id= $request->bank_id;
                $bank_statement->school_id= Auth::user()->school_id;
                $bank_statement->payment_method= $request->payment_method;
                $bank_statement->save();


                $current_balance= SmBankAccount::find($request->bank_id);
                $current_balance->current_balance=$after_balance;
                $current_balance->update();
            }

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

    

    public function itemIssueList(Request $request)
    {

        try {
            $roles = InfixRole::when((generalSetting()->with_guardian !=1), function ($query) {
                $query->where('id', '!=', 3);
            })->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $classes = SmClass::where('school_id', Auth::user()->school_id)->where('academic_id',getAcademicId())->get();
            $itemCat = SmItemCategory::where('school_id', Auth::user()->school_id)->get();
            $issuedItems = SmItemIssue::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->orderby('id','DESC')->get();

  
            return view('backEnd.inventory.issueItemList', compact('issuedItems', 'roles', 'classes', 'itemCat'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getItemByCategory(Request $request)
    {
        $allitems = SmItem::where('item_category_id', '=', $request->id)->where('school_id', Auth::user()->school_id)->get();
        $items = [];
        foreach ($allitems as $item) {
            $items[] = SmItem::find($item->id);
        }
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendResponse($items, null);
        }
        return response()->json([$items]);
    }


    public function saveItemIssueData(SmItemIssueRequest $request)
    {

        try {
            $issue_to = '';
            if ($request->role_id == 2) {
                if (!empty($request->student)) {
                    $issue_to = $request->student;
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } else {
                if (!empty($request->staff_id)) {
                    $issue_to = $request->staff_id;
                } else {
                
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
            $user = Auth()->user();

            $itemIssue = new SmItemIssue();
            $itemIssue->role_id = $request->role_id;
            $itemIssue->issue_to = $issue_to;
            $itemIssue->issue_by = auth()->user()->id;
            $itemIssue->item_category_id = $request->item_category_id;
            $itemIssue->item_id = $request->item_id;
            $itemIssue->issue_date = date('Y-m-d', strtotime($request->issue_date));
            $itemIssue->due_date = date('Y-m-d', strtotime($request->due_date));
            $itemIssue->quantity = $request->quantity;
            $itemIssue->issue_status = 'I';
            $itemIssue->note = $request->description;
            $itemIssue->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $itemIssue->un_academic_id = getAcademicId();
            }else{
                $itemIssue->academic_id = getAcademicId();
            }
            $results = $itemIssue->save();
            $itemIssue->toArray();
            if ($results) {

                $items = SmItem::find($request->item_id);
                $items->total_in_stock = $items->total_in_stock - $request->quantity;
                if(moduleStatusCheck('University')){
                    $items->un_academic_id = getAcademicId();
                }
                $items->update(); 

            } 
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function returnItemView(Request $request, $id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($id, null);
            }
            return view('backEnd.inventory.returnItemView', compact('id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function returnItem(Request $request, $id)
    {
        try {
            $iuusedItem = SmItemIssue::select('item_id', 'quantity')->where('id', $id)->first();
            $items = SmItem::find($iuusedItem->item_id);
            $items->total_in_stock = $items->total_in_stock + $iuusedItem->quantity;
            if(moduleStatusCheck('University')){
                $items->un_academic_id = getAcademicId();
            }else{
                $items->academic_id = getAcademicId();
            }
            $result = $items->update();
            if ($result) {
                $itemissue = SmItemIssue::find($id);
                $itemissue->issue_status = 'R';
                $itemissue->update();

            } 

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewSellPayments($id)
    {

        try {
            $payments = SmInventoryPayment::with('paymentMethods')->where('item_receive_sell_id', $id)->where('payment_type', 'S')->get();

            return view('backEnd.inventory.viewSellPayments', compact('payments', 'id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteSellPayment()
    {

        try {
            $sell_payment_id = $_POST['sell_payment_id'];
            $paymentHistory = SmInventoryPayment::find($sell_payment_id);
            
            $item_receive_sell_id = $paymentHistory->item_receive_sell_id;
            $amount = $paymentHistory->amount;
            $itemSellData = SmItemSell::find($item_receive_sell_id);
            $itemSellData->total_due = $itemSellData->total_due + $amount;
            $itemSellData->total_paid = $itemSellData->total_paid - $amount;

            if(paymentMethodName($itemSellData->payment_method)){
                $bank=SmBankAccount::where('id',$itemSellData->account_id)
                ->where('school_id',Auth::user()->school_id)
                ->first();
                $after_balance= $bank->current_balance - $amount;

                $current_balance= SmBankAccount::find($itemSellData->account_id);
                $current_balance->current_balance=$after_balance;
                $current_balance->update();

                $delete_balance = SmBankStatement::where('item_sell_id',$itemSellData->id)
                                ->where('item_sell_bank_statement_id',$paymentHistory->id)
                                ->where('amount',$amount)
                                ->delete();
            }

          SmAddIncome::where('inventory_id',$paymentHistory->id)->delete();


            // check if total due is greater than 0
            if (($itemSellData->total_due + $amount) > 0) {
                $itemSellData->paid_status = 'PP';
            }

            // check if total due is equal to 0
            if (($itemSellData->total_due + $amount) == 0) {
                $itemSellData->paid_status = 'P';
            }

            $itemSellData->update();

           SmInventoryPayment::destroy($sell_payment_id);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function cancelItemSellView($id)
    {

        try {
            return view('backEnd.inventory.cancelItemSellView', compact('id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function cancelItemSell($id)
    {
        try {
            $itemSell = SmItemSell::find($id);
            $itemSell->paid_status = 'S';
            $results = $itemSell->update();
            SmAddIncome::where('item_sell_id',$itemSell->id)
                    ->where('school_id', Auth::user()->school_id)
                    ->delete();

            if(paymentMethodName($itemSell->payment_method)){
                $reset_balance = SmBankStatement::where('item_sell_id',$itemSell->id)
                                ->where('school_id',Auth::user()->school_id)
                                ->sum('amount');

                    $bank=SmBankAccount::where('id',$itemSell->account_id)
                    ->where('school_id',Auth::user()->school_id)
                    ->first();
                    $after_balance= $bank->current_balance - $reset_balance;

                    $current_balance= SmBankAccount::find($itemSell->account_id);
                    $current_balance->current_balance=$after_balance;
                    $current_balance->update();

                   SmBankStatement::where('item_sell_id',$itemSell->id)
                                    ->where('school_id',Auth::user()->school_id)
                                    ->delete();
            }

            if ($results) {
                $itemSellChild = SmItemSellChild::where('item_sell_id', $id)->where('school_id', Auth::user()->school_id)->get();
                if (!empty($itemSellChild)) {
                    foreach ($itemSellChild as $value) {
                        $items = SmItem::find($value->item_id);
                        $items->total_in_stock = $items->total_in_stock + $value->quantity;
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
