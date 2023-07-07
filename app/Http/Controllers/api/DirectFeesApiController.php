<?php

namespace App\Http\Controllers\api;

use App\SmBankAccount;
use App\SmPaymentMethhod;
use App\SmBankPaymentSlip;
use App\SmGeneralSettings;
use App\Models\FeesInvoice;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DirectFeesInstallmentAssign;
use App\Models\DireFeesInstallmentChildPayment;
use Modules\University\Entities\UnFeesInstallmentAssign;
use Modules\University\Entities\UnFeesInstallAssignChildPayment;
use App\Scopes\ActiveStatusSchoolScope;

class DirectFeesApiController extends Controller
{
    public function getInstallments($record_id){

        $student_record = StudentRecord::find($record_id);
        $data = [];
        $invoice = FeesInvoice::where('school_id', $student_record->school_id)->first();
        $data['prefix'] = @$invoice->prefix; 
        $data['start_form'] = @$invoice->start_form - 1;
        if(moduleStatusCheck('University')){
           $data['feesInstallments'] = UnFeesInstallmentAssign::where('un_academic_id',$student_record->un_academic_id)->where('un_semester_label_id', $student_record->un_semester_label_id)->where('record_id', $student_record->id)->get();
        }
        elseif(directFees()){
            $data['feesInstallments'] = DirectFeesInstallmentAssign::with('payments','installment')->where('academic_id',$student_record->academic_id)->where('record_id', $student_record->id)->get();
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function makePayment($record_id){

        $data = [];
        $student_record = StudentRecord::find($record_id);
        $invoice = FeesInvoice::where('school_id', $student_record->school_id)->first();
        $data['prefix'] = @$invoice->prefix; 
        $data['start_form'] = @$invoice->start_form - 1;
        $data['banks'] = SmBankAccount::withOutGlobalScope(ActiveStatusSchoolScope::class)->where('school_id', $student_record->school_id)->get(['id','bank_name']);
  
        if(moduleStatusCheck('University')){
            $feesInstallments = UnFeesInstallmentAssign::where('un_academic_id',$student_record->un_academic_id)->where('un_semester_label_id', $student_record->un_semester_label_id)->where('record_id', $student_record->id)->get();
        }
        elseif(directFees()){
            $feesInstallments = DirectFeesInstallmentAssign::with('payments')->where('academic_id',$student_record->academic_id)->where('record_id', $student_record->id)->get();
        }
        $data['total_amount'] = $feesInstallments->sum('amount');
        $data['total_paid'] = $feesInstallments->sum('paid_amount');
        $data['total_due'] =  $feesInstallments->sum('amount') - ( $feesInstallments->sum('discount_amount') + $data['total_paid']);
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function submitPayment(Request $request, $record_id){

        $data = [];
        $request_amount = $request->amount;
        $bank_id = $request->bank_id;
        $student_record = StudentRecord::find($record_id);
        $student_id = $student_record->student_id;
        $after_paid = $request_amount;

        if(moduleStatusCheck('University')){
            $feesInstallments = UnFeesInstallmentAssign::where('un_academic_id',$student_record->un_academic_id)->where('un_semester_label_id', $student_record->un_semester_label_id)->where('record_id', $student_record->id)->get();
            $installments = UnFeesInstallmentAssign::where('record_id', $record_id)->get();
        }
        elseif(directFees()){
            $feesInstallments = DirectFeesInstallmentAssign::with('payments')->where('academic_id',$student_record->academic_id)->where('record_id', $student_record->id)->get();
            $installments = DirectFeesInstallmentAssign::where('record_id', $record_id)->get();
        }

        $total_paid = $installments->sum('paid_amount');
        $total_amount = $installments->sum('amount');
        $total_discount = $installments->sum('discount_amount');
        $balace_amount = $total_amount - ($total_discount +  $total_paid);
        if($balace_amount <  $request_amount){
            return response()->json([
                'messege' => "Amount is higher than due amount, Pay equal or less !"
            ], 422);
        }elseif( 0 >= $request_amount){
            return response()->json([
                'messege' => "Please Pay equal or less not .$request_amount. "
            ], 422);
        }

        if($request->payment_mode=="bank"){
            if($request->bank_id==''){
                return response()->json([
                    'messege' => "Please Select Bank"
                ], 422);
            }
        }

        $fileName = "";
        if ($request->file('slip') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('sli                                   p');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                return response()->json([
                    'messege' => 'Max upload file size '. $maxFileSize .' Mb is set in system'
                ], 422);
            }
            $file = $request->file('slip');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/bankSlip/', $fileName);
            $fileName = 'public/uploads/bankSlip/' . $fileName;
        }

        $date = strtotime($request->date);
        $newformat = date('Y-m-d', $date);

        foreach($installments as $installment){
            if($after_paid <= 0){
                break;
            }
            $installment_due = $installment->amount - ($installment->discount_amount +  $installment->paid_amount);
            if($installment_due && $after_paid > 0){
                if($installment_due >= $after_paid){
                    $paid_amount = $after_paid;
                }else{
                    $paid_amount  = $installment_due;
                }
    
                $payment_mode_name=ucwords($request->payment_mode);
                $payment_method=SmPaymentMethhod::where('method',$payment_mode_name)->first();

                $payment = new SmBankPaymentSlip();
                $payment->date = $newformat;
                $payment->amount = $paid_amount;
                $payment->note = $request->note;
                $payment->slip = $fileName;
                $payment->student_id = $student_record->student_id;
                $payment->payment_mode = $request->payment_mode;
                if($payment_method->id==3){
                    $payment->bank_id = $request->bank_id;
                }
                $payment->academic_id = $student_record->academic_id;
                if(moduleStatusCheck('University')){
                    $payment->un_academic_id= getAcademicId();
                    $payment->un_fees_installment_id  = $installment->id;
                    $payment->un_semester_label_id = $student_record->un_semester_label_id;
                    $installment = UnFeesInstallmentAssign::find($installment->id);
                    $installment->payment_date =  $newformat;
                    $installment->payment_mode = $request->payment_mode;
                    $installment->note = $request->note;
                    $installment->slip = $fileName;
                    $installment->active_status = 0;
                    if($payment_method->id==3){
                        $installment->bank_id  = $request->bank_id;
                    }
                    $installment->save();

                    $payable_amount =  discountFeesAmount($installment->id);
                    $sub_payment = $installment->payments->sum('paid_amount');
                
                    $last_inovoice = UnFeesInstallAssignChildPayment::where('school_id',auth()->user()->school_id)->max('invoice_no');
                    $new_subPayment = new UnFeesInstallAssignChildPayment();
                    $new_subPayment->un_fees_installment_assign_id = $installment->id;
                    $new_subPayment->invoice_no = ( $last_inovoice + 1 ) ?? 1;
                    $new_subPayment->amount = $paid_amount;
                    $new_subPayment->paid_amount = $paid_amount;
                    $new_subPayment->payment_date = $newformat;
                    $new_subPayment->payment_mode =  $request->payment_mode;
                    $new_subPayment->note = $request->note;
                    $new_subPayment->slip = $fileName;
                    $new_subPayment->active_status = 0;
                    $new_subPayment->bank_id = $request->bank_id;
                    $new_subPayment->discount_amount = 0;
                    $new_subPayment->fees_type_id =  $installment->fees_type_id;
                    $new_subPayment->student_id = $student_record->student_id;
                    $new_subPayment->record_id = $request->record_id;
                    $new_subPayment->un_semester_label_id = $student_record->un_semester_label_id;;
                    $new_subPayment->un_academic_id = getAcademicId();
                    $new_subPayment->created_by = Auth::user()->id;
                    $new_subPayment->updated_by =  Auth::user()->id;
                    $new_subPayment->school_id = Auth::user()->school_id;
                    $new_subPayment->balance_amount = ($payable_amount - ($sub_payment + $paid_amount)); 
                    $new_subPayment->save();
                    $payment->child_payment_id = $new_subPayment->id;

                }
                elseif(directFees()){
                    $payment->class_id = $student_record->class_id;
                    $payment->section_id = $student_record->section_id;
                    $payment->record_id = $student_record->id;
                    $payment->school_id = $student_record->school_id;
                    $installment = DirectFeesInstallmentAssign::find($installment->id);
                    $installment->payment_date =  $newformat;
                    $installment->payment_mode = $request->payment_mode;
                    $installment->note = $request->note;
                    $installment->slip = $fileName;
                    $installment->active_status = 0;
                    if($payment_method->id==3){
                        $installment->bank_id  = $request->bank_id;
                    }
                    $installment->save();
                    $payable_amount =  discountFees($installment->id);
                    $sub_payment = $installment->payments->sum('paid_amount');
                
                    $last_inovoice = DireFeesInstallmentChildPayment::where('school_id',auth()->user()->school_id)->max('invoice_no');
                    $new_subPayment = new DireFeesInstallmentChildPayment();
                    $new_subPayment->direct_fees_installment_assign_id = $installment->id;
                    $new_subPayment->invoice_no = ( $last_inovoice +1 ) ?? 1;
                    $new_subPayment->amount = $paid_amount;
                    $new_subPayment->paid_amount = $paid_amount;
                    $new_subPayment->payment_date = $newformat;
                    $new_subPayment->payment_mode =  $request->payment_mode;
                    $new_subPayment->note = $request->note;
                    $new_subPayment->slip = $fileName;
                    $new_subPayment->active_status = 0;
                    $new_subPayment->bank_id = $request->bank_id;
                    $new_subPayment->discount_amount = 0;
                    $new_subPayment->fees_type_id =  $installment->fees_type_id;
                    $new_subPayment->student_id = $student_record->student_id;
                    $new_subPayment->record_id = $record_id;
                    $new_subPayment->school_id = $student_record->school_id;
                    $new_subPayment->balance_amount = ( $payable_amount - ($sub_payment + $paid_amount) ); 
                    $new_subPayment->save();
                    $payment->child_payment_id = $new_subPayment->id;
                    $payment->installment_id = $installment->id;
                }
                $payment->save();
                $after_paid -= ( $paid_amount);
            }
        }

        return response()->json([
            'messege' => "Payment Added, Please Wait for approval"
        ], 200);
      
    }
}
