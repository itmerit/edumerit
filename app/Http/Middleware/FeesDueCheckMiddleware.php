<?php

namespace App\Http\Middleware;

use Closure;
use App\SmFeesAssign;
use Illuminate\Http\Request;
use App\Models\DirectFeesInstallment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Modules\Fees\Entities\FmFeesInvoice;
use App\Models\DirectFeesInstallmentAssign;
use Symfony\Component\HttpFoundation\Response;

class FeesDueCheckMiddleware
{
    
    public function handle(Request $request, Closure $next): Response
    {   
        if(!generalSetting()->due_fees_login){
            $now = (date("Y-m-d"));
            $user = auth()->user();
            $due_fees = false;
            $cache_key = 'have_due_fees_'.$user->id ;
            $due_date = null;
            $fees_info = [];
            Cache::forget($cache_key);
            if(auth()->check() && auth()->user()->role_id == 2 && (!auth()->user()->loginRestricted)){
                if(moduleStatusCheck('Fees') && generalSetting()->fees_status){
                    $due_fees = FmFeesInvoice::where('academic_id',getAcademicId())->where('payment_status', '=','not')->where('student_id',$user->student->id)->orderBy('due_date', 'desc')->first('due_date');
                    $due_date = @$due_fees->due_date;       
                }
            
                elseif(generalSetting()->fees_status == 0 && directFees()){
                    $due_installment = DirectFeesInstallmentAssign::where('active_status','!=',1)->where('academic_id',getAcademicId())->where('student_id', auth()->user()->student->id)->orderBy('due_date', 'desc')->first();
                    $due_date = @$due_installment->due_date;
                }
                elseif(generalSetting()->fees_status == 0){
                    $all_fees = SmFeesAssign::where('academic_id',getAcademicId())->where('student_id',$user->student->id)
                    ->whereHas('feesGroupMaster', function($q){
                        return $q->orderBy('date', 'desc');
                    })->get();
                    foreach($all_fees as $assign_fees){
                        $paid = SmFeesAssign::feesPayment($assign_fees->feesGroupMaster->feesTypes->id,$assign_fees->student_id,$assign_fees->record_id)->sum('amount');
                        $fine = SmFeesAssign::feesPayment($assign_fees->feesGroupMaster->feesTypes->id,$assign_fees->student_id,$assign_fees->record_id)->sum('fine');
                        $total_paid = $assign_fees->applied_discount + $paid ;
                        $total_payable_amount = $assign_fees->feesGroupMaster->amount;
                        $rest_amount = $assign_fees->feesGroupMaster->amount - $total_paid;
                        $balance_amount = $rest_amount + $fine;
                        if($balance_amount){
                            $due_date =  @$assign_fees->feesGroupMaster->date;
                        }   
                    }
                }
                if($due_date){
                    if($now > $due_date){
                        Cache::rememberForever( $cache_key , function (){
                            return true;
                        });
                    }else{
                        Cache::forget($cache_key);
                    }
                }
            }
            elseif(auth()->check() && auth()->user()->role_id == 3 && (!auth()->user()->loginRestricted)){
                $student_ids =  auth()->user()->parent->childrens->pluck('id');
                if(moduleStatusCheck('Fees') && generalSetting()->fees_status){
                    foreach($student_ids as $student_id){
                        $due_fees = FmFeesInvoice::where('academic_id',getAcademicId())->where('payment_status', '=','not')->where('student_id',$student_id)->orderBy('due_date', 'desc')->first('due_date');
                        if($due_fees &&  $now > $due_fees->due_date){
                            $fees_info[] =  $student_id;
                        } 
                    }
                    
                }
                elseif(generalSetting()->fees_status == 0 && directFees()){
                    foreach($student_ids as $student_id){
                        $due_installment = DirectFeesInstallmentAssign::where('active_status','!=',1)->where('academic_id',getAcademicId())->where('student_id', $student_id)->orderBy('due_date', 'desc')->first();
                        if($due_installment &&  $now > $due_installment->due_date){
                            $fees_info[] =  $student_id;
                        } 
                    }
                
                }
                elseif(generalSetting()->fees_status == 0){
                    foreach($student_ids as $student_id){
                        $all_fees = SmFeesAssign::where('academic_id',getAcademicId())->where('student_id',$student_id)
                        ->whereHas('feesGroupMaster', function($q){
                            return $q->orderBy('date', 'desc');
                        })->get();

                        foreach($all_fees as $assign_fees){
                            $paid = SmFeesAssign::feesPayment($assign_fees->feesGroupMaster->feesTypes->id,$assign_fees->student_id,$assign_fees->record_id)->sum('amount');
                            $fine = SmFeesAssign::feesPayment($assign_fees->feesGroupMaster->feesTypes->id,$assign_fees->student_id,$assign_fees->record_id)->sum('fine');
                            $total_paid = $assign_fees->applied_discount + $paid ;
                            $total_payable_amount = $assign_fees->feesGroupMaster->amount;
                            $rest_amount = $assign_fees->feesGroupMaster->amount - $total_paid;
                            $balance_amount = $rest_amount + $fine;

                            if($balance_amount &&  $now > $assign_fees->feesGroupMaster->date){
                                $fees_info[] =  $student_id;
                            }  
                        }
                    }
                }
              
                
                Cache::rememberForever($cache_key , function() use ($fees_info){
                    return $fees_info;
                });
               
              
            }   
            return $next($request);
        }
        else{
            return $next($request);
        }
        
        
    }
}
