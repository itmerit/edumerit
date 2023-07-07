<?php

namespace App\Traits;

use Carbon\Carbon;
use App\SmFeesCarryForward;
use Illuminate\Http\Request;
use App\Models\FeesCarryForwardSettings;
use App\Models\DirectFeesInstallmentAssign;
use App\Http\Controllers\Admin\FeesCollection\SmFeesController;

trait FeesCarryForward{

    public function feesCarryForwardInstallment($studentRecord, $fees_master, $assign_fees, $installMentID, $payableAmount = null){
        $carryForward = SmFeesCarryForward::where('student_id', $studentRecord->id)->first();
        if(!$carryForward){
            return ;
        }

        $settings = FeesCarryForwardSettings::first();
        
        if(Carbon::now()->format('Y-m-d') <= $carryForward->due_date){
            if($carryForward->balance_type == 'due' && $carryForward->balance > 0){
                $dueBalance = $carryForward->balance;
    
                $assignInstallment = new DirectFeesInstallmentAssign();
                $assignInstallment->fees_master_ids = json_encode([$fees_master->id]);
                $assignInstallment->assign_ids = json_encode([$assign_fees]);
                
                $assignInstallment->fees_installment_id = $installMentID;
                $assignInstallment->amount =  $dueBalance;
                $assignInstallment->due_date = $carryForward->due_date;
                $assignInstallment->fees_type_id = $fees_master->fees_type_id;
                $assignInstallment->student_id = $studentRecord->student_id;
                $assignInstallment->record_id = $studentRecord->id;
                $assignInstallment->academic_id = getAcademicId();
                $assignInstallment->school_id = auth()->user()->school_id;
                $assignInstallment->save();
    
                $updateCarry = SmFeesCarryForward::where('student_id', $studentRecord->id)->first();
                $updateCarry->balance = NULL;
                $updateCarry->balance_type = 'add';
                $updateCarry->update();
    
                carryForwardLog($studentRecord->id, $dueBalance, 'due', 'Fees Payment', 'installment');
            }else{
                if($payableAmount <= $carryForward->balance){
                    $addBalance = $carryForward->balance - $payableAmount;
                    $request = app()->make(Request::class);
                    $request->merge([
                        'date' => date("Y-m-d H:i:s"),
                        'record_id' => $studentRecord->id,
                        'request_amount' => $payableAmount,
                        'real_amount' => $payableAmount,
                        'student_id' => $studentRecord->student_id,
                        'payment_mode' => $settings->payment_gateway
                    ]);
                     
    
                    $feesController = new SmFeesController();
                    $feesController->addPayment($request);
    
                    $updateCarry = SmFeesCarryForward::where('student_id', $studentRecord->id)->first();
                    $updateCarry->balance = $addBalance;
                    $updateCarry->balance_type = 'add';
                    $updateCarry->update();
    
                    carryForwardLog($studentRecord->id, $payableAmount, 'due', 'Fees Payment Added', 'installment');
                    carryForwardLog($studentRecord->id, $addBalance, 'add', 'Fees Payment and Carry Ballance Added', 'installment');
                }else{
                    $request = app()->make(Request::class);
                    $request->merge([
                        'date' => date("Y-m-d H:i:s"),
                        'record_id' => $studentRecord->id,
                        'request_amount' => $carryForward->balance,
                        'real_amount' => $carryForward->balance,
                        'student_id' => $studentRecord->student_id,
                        'payment_mode' => $settings->payment_gateway,
                    ]);
    
                    $feesController = new SmFeesController();
                    $feesController->addPayment($request);
    
                    $updateCarry = SmFeesCarryForward::where('student_id', $studentRecord->id)->first();
                    $updateCarry->balance = NULL;
                    $updateCarry->balance_type = 'add';
                    $updateCarry->update();
    
                    carryForwardLog($studentRecord->id, $carryForward->balance, 'due', 'Fees Payment', 'installment');
                }
            }
            return true;
        }else{
            return ;
        }
    }
}