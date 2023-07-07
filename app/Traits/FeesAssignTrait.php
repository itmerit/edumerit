<?php

namespace App\Traits;

use App\SmFeesType;
use App\SmFeesGroup;
use App\SmFeesAssign;
use App\SmFeesMaster;
use App\SmFeesDiscount;
use App\Models\StudentRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\FeesInstallmentCredit;
use Modules\University\Entities\UnSubject;
use Modules\University\Entities\UnSemesterLabel;
use Modules\University\Entities\UnFeesInstallment;
use Modules\University\Entities\UnFeesInstallmentAssign;
use Modules\University\Entities\UnFeesInstallAssignChildPayment;

trait FeesAssignTrait
{

    public function assignSubjectFees($student_record, $subject_id, $semester_label_id, $fees_group_id = null, $withOutSubject = null)
    {
        try{
            $studentRecord = StudentRecord::find($student_record);
            $already_paid = 0;  
            if ($fees_group_id != null) {
                $fees_master = SmFeesMaster::where('fees_group_id', $fees_group_id)
                ->first();
            } else {
                $fees_master = SmFeesMaster::where('un_semester_label_id',$semester_label_id)->where('un_subject_id', $subject_id)->first();
                    if(is_null($fees_master)){
                        $subeject = UnSubject::find($subject_id);
                        $sem_label = UnSemesterLabel::find($semester_label_id);
                        $fees_group = new SmFeesGroup();
                        $fees_group->name = $subeject->subject_name;
                        $fees_group->school_id = Auth::user()->school_id;
                        $fees_group->un_academic_id = getAcademicId();
                        $fees_group->save();
                        $feesGroupId = $fees_group->id;
                        $fees_type = new SmFeesType();
                        $fees_type->name =$subeject->subject_name;
                        $fees_type->fees_group_id = $feesGroupId;
                        $fees_type->school_id = Auth::user()->school_id;
                        $fees_type->un_academic_id = getAcademicId();
                        $fees_type->save();
                        $feesTypeId = $fees_type->id;
            
                        $year = date('Y');
                        $amount = ($subeject->number_of_hours * $sem_label->fees_per_hour);
                        $fees_master = new SmFeesMaster();
                        $fees_master->fees_group_id = $fees_type->fees_group_id;
                        $fees_master->fees_type_id = $feesTypeId;
                        $fees_master->un_subject_id = $subeject->id;
                        $fees_master->un_semester_label_id = $semester_label_id;
                        $fees_master->date = date('Y-m-d', strtotime( $year . '-01-01'));
                        $fees_master->school_id = Auth::user()->school_id;
                        $fees_master->un_academic_id = getAcademicId();
                        $fees_master->amount = $amount;
                        $fees_master->save();
                    }
            }
            if ($fees_master) {
                $exist = SmFeesAssign::where('fees_master_id', $fees_master->id)
                    ->where('un_semester_label_id', $semester_label_id)
                    ->where('un_academic_id', getAcademicId())
                    ->where('record_id', $student_record)
                    ->first();
                   
                if (!$exist) {
                    $studentRecord = StudentRecord::find($student_record);
                    $assign_fees = new SmFeesAssign();
                    $assign_fees->fees_amount = $fees_master->amount;
                    $assign_fees->fees_master_id = $fees_master->id;
                    $assign_fees->student_id = $studentRecord->student_id;
                    $assign_fees->record_id = $studentRecord->id;
                    $assign_fees->un_academic_id = getAcademicId();
                    $assign_fees->school_id = auth()->user()->school_id;
                    $assign_fees->un_semester_label_id = $semester_label_id;
                    $assign_fees->save();
    
                    $installments = UnFeesInstallment::where('un_semester_label_id', $semester_label_id)->get();
                    
                    if (count($installments)>0) {
                        foreach ($installments as $installment) {
                            $checkExist = UnFeesInstallmentAssign::where('un_semester_label_id', $semester_label_id)
                                ->where('un_academic_id', getAcademicId())
                                ->where('record_id', $studentRecord->id)
                                ->where('student_id', $studentRecord->student_id)
                                ->where('un_fees_installment_id', $installment->id)
                                ->first();
                            if ($checkExist) {
                                $old_master = json_decode($checkExist->fees_master_ids);
                                $old_assign = json_decode($checkExist->assign_ids);
    
                                array_push($old_assign, $assign_fees->id);
                                array_push($old_master, $fees_master->id);
    
                                $assignInstallment = $checkExist;
                                $assignInstallment->fees_master_ids = json_encode($old_master);
                                $assignInstallment->assign_ids = json_encode($old_assign);
                            } else {
                                $assignInstallment = new UnFeesInstallmentAssign();
                                $assignInstallment->fees_master_ids = json_encode([$fees_master->id]);
                                $assignInstallment->assign_ids = json_encode([$assign_fees->id]);
                            }
    
                            $assignInstallment->un_fees_installment_id = $installment->id;
                            $assignInstallment->amount += (($fees_master->amount * $installment->percentange) / 100);
                            $assignInstallment->due_date = $installment->due_date;
                            $assignInstallment->fees_type_id = $fees_master->fees_type_id;
                            $assignInstallment->student_id = $studentRecord->student_id;
                            $assignInstallment->record_id = $studentRecord->id;
                            $assignInstallment->un_semester_label_id = $semester_label_id;
                            $assignInstallment->un_academic_id = getAcademicId();
                            $assignInstallment->school_id = auth()->user()->school_id;
                            $assignInstallment->save();
                        }
                    }
                }
            }

            
        }
        catch(\Exception $e){
            Log::info($e);
         }
    }

    public function adjustCreditWithFees($record_id){
        $already_paid = 0;  
        $studentRecord = StudentRecord::find($record_id);
        $have_credit = $studentRecord->student->feesCredits->sum('amount'); 
            if($have_credit){
                $assigned_ins = UnFeesInstallmentAssign::where('record_id', $studentRecord->id)->get();
                $student_id = $studentRecord->student_id;
                $request_amount = $have_credit;
                $after_paid = $request_amount;
                $date = strtotime(Carbon::now());
                $newformat = date('Y-m-d', $date);
                $total_paid = $assigned_ins->sum('paid_amount');
                $total_amount = $assigned_ins->sum('amount');
                $total_discount = $assigned_ins->sum('discount_amount');
                $balace_amount = $total_amount - ($total_discount +  $total_paid);
                if($balace_amount > 0){
                    foreach($assigned_ins as $key=> $installment){
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
                
                            $payment_mode_name = ucwords("Credit");
                            $installment = UnFeesInstallmentAssign::find($installment->id);
                            $installment->payment_date =  $newformat;
                            $installment->payment_mode = $payment_mode_name;
                            $installment->note = null;
                            $installment->slip = null;
                            $installment->active_status = 1;
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
                            $new_subPayment->payment_mode =  $payment_mode_name;
                            $new_subPayment->active_status = 1;
                            $new_subPayment->discount_amount = 0;
                            $new_subPayment->fees_type_id =  $installment->fees_type_id;
                            $new_subPayment->student_id = $studentRecord->student_id;
                            $new_subPayment->record_id =  $studentRecord->id;
                            $new_subPayment->un_semester_label_id = $studentRecord->un_semester_label_id;
                            $new_subPayment->un_academic_id = getAcademicId();
                            $new_subPayment->created_by = Auth::user()->id;
                            $new_subPayment->updated_by =  Auth::user()->id;
                            $new_subPayment->school_id = Auth::user()->school_id;
                            $new_subPayment->balance_amount = ($payable_amount - ($sub_payment + $paid_amount)); 
                            $new_subPayment->save();

                            if(($sub_payment + $paid_amount) == $payable_amount){
                                $installment->active_status = 1;
                            }else{
                                $installment->active_status = 2;
                            }
                            $installment->paid_amount = $sub_payment + $paid_amount;
                            $installment->save();
                            $after_paid -= ( $paid_amount);
                            $already_paid += $paid_amount;
                        }
                    }
                }
            
                $feesCredits =  $studentRecord->student->feesCredits ;
                $feesCreditSum = $studentRecord->student->feesCredits->sum('amount');
                foreach($feesCredits as $credit){
                    if($credit->amount  && $feesCreditSum){
                        $credit->amount = $credit->amount - $already_paid;
                        $feesCreditSum -= $already_paid;
                        $credit->save();
                    }
                }
                
            }

    }

    public function assignDiscount($discount_id,$record_id){
        
        $fees_discount = SmFeesDiscount::find($discount_id);
        $installments = UnFeesInstallmentAssign::where('record_id',$record_id)->get();
        $discount_amount = $fees_discount->amount;
        $extra_credit = 0;
        if($fees_discount && $installments){
            foreach($installments as $feesInstallment){
                if($feesInstallment->active_status == 0){
                    $feesInstallment->fees_discount_id = $discount_id; 
                    $feesInstallment->discount_amount = $discount_amount; 
                    $feesInstallment->save();
                }
               
                else{
                    $payable_amount =  discountFeesAmount($feesInstallment->id);
                    if($payable_amount){
                        if($payable_amount > $discount_amount ){
                            $feesInstallment->fees_discount_id = $discount_id; 
                            $feesInstallment->discount_amount =  $discount_amount; 
                            $feesInstallment->save();
                        }elseif($payable_amount == $discount_amount){
                            $feesInstallment->fees_discount_id = $discount_id; 
                            $feesInstallment->discount_amount =  $discount_amount; 
                            $feesInstallment->active_status = 1;
                            $feesInstallment->save();
                        }
                        elseif($payable_amount < $discount_amount){
                            $feesInstallment->fees_discount_id = $discount_id; 
                            $feesInstallment->discount_amount =  $payable_amount; 
                            $extra_credit +=  ($discount_amount - $payable_amount);
                        }
                    }  
                }
                
            }

            if($extra_credit> 0){
                $new_credit = new FeesInstallmentCredit();
                $new_credit->student_id = $feesInstallment->student_id;
                $new_credit->student_record_id = $record_id;
                $new_credit->school_id = $feesInstallment->school_id;
                $new_credit->amount = $fees_discount->amount;
                $new_credit->save();
            }
        }
    }

    public function feesMasterUnAssign($record_id, $semester_label_id, $fees_group_id){
        $studentRecord = StudentRecord::find($record_id);
        $fees_master = SmFeesMaster::where('fees_group_id', $fees_group_id)->first();
        $installments  = UnFeesInstallmentAssign::where('record_id',$record_id)->where('un_semester_label_id',$semester_label_id)->get();
        $selectedAssignFees = SmFeesAssign::where('un_semester_label_id', $semester_label_id)
                                    ->where('un_academic_id', getAcademicId())
                                    ->where('record_id', $record_id)
                                    ->where('fees_master_id',$fees_master->id)
                                    ->first();
        $have_payemts  = UnFeesInstallmentAssign::where('record_id',$record_id)->where('un_semester_label_id',$semester_label_id)->where('active_status', '!=', 0)->first();                           
        if($installments){
                if($selectedAssignFees && is_null($have_payemts)){
                    $un_assign_amount = $fees_master->amount;
                    foreach($installments as $ins){
                        $installment = UnFeesInstallment::find($ins->un_fees_installment_id);
                        if($installment){
                            $ins->amount -= (($un_assign_amount * $installment->percentange) / 100);
                            $ins->amount->save();
                        } 
                    } 
                    $selectedAssignFees->delete();                   
          
            }
        }
    }
}
