<?php 
namespace App\PaymentGateway;

use App\User;
use Stripe\Charge;
use Stripe\Stripe;
use App\SmAddIncome;
use App\SmFeesPayment;
use App\SmPaymentMethhod;
use App\SmPaymentGatewaySetting;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\DirectFeesInstallmentAssign;
use Modules\Lms\Entities\CoursePurchaseLog;
use Modules\Wallet\Entities\WalletTransaction;
use App\Models\DireFeesInstallmentChildPayment;
use Modules\Fees\Http\Controllers\FeesExtendedController;

class StripePayment{

    public function handle($data)
    {
        $payment_setting = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')->where('school_id', Auth::user()->school_id)->first();

        Stripe::setApiKey($payment_setting->gateway_secret_key);

        $amount = $data['amount'];
        if(array_key_exists('service_charge', $data)) {
            $amount = $data['amount'] + $data['service_charge'];
        }

        Charge::create([
            "amount" => $amount * 100,
            "currency" => "usd",
            "source" => $data['stripeToken'],
            "description" => $data['description']
        ]);

        if($data['type'] == "direct_fees_total"){
            $request_amount = $data['request_amount'];
            $after_paid = $request_amount;
            $installments = DirectFeesInstallmentAssign::where('record_id', $data['record_id'])->get();
            $total_paid = $installments->sum('paid_amount');
            $total_amount = $installments->sum('amount');
            $total_discount = $installments->sum('discount_amount');
            $balace_amount = $total_amount - ($total_discount +  $total_paid);
            if($balace_amount <  $request_amount){
                Toastr::error('Amount is greater than due', 'Failed');
                if(Auth::user()->role_id == 2){
                    return redirect()->to(url('student-fees'));
                }else{
                    return redirect()->to(url('parent-fees'.'/'.$installment->student_id));
                }
            }
            
            $newformat = date('Y-m-d');
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

                   $fees_payment = new SmFeesPayment();
                   $fees_payment->student_id = $installment->student_id;
                   $fees_payment->fees_discount_id = !empty($request->fees_discount_id) ? $request->fees_discount_id : "";
                   $fees_payment->discount_amount = !empty($request->applied_amount) ? $request->applied_amount : 0;
                   $fees_payment->amount = $paid_amount;
                   $fees_payment->payment_date = date('Y-m-d');
                   $fees_payment->payment_mode = $data['method'];
                   $fees_payment->created_by = Auth::id();
                   $fees_payment->school_id = Auth::user()->school_id;
                   $fees_payment->record_id = $installment->record_id;
                   $fees_payment->academic_id = getAcademicid();
                   $fees_payment->direct_fees_installment_assign_id = $installment->id;
               
                    $payment_mode_name=ucwords($data['method']);
                    $payment_method= SmPaymentMethhod::where('method',$payment_mode_name)->first();
                    $installment = DirectFeesInstallmentAssign::find($installment->id);
                    $installment->payment_date =  $newformat;
                    $installment->payment_mode = $data['method'];
                    

                    $payable_amount =  discountFees($installment->id);
                    $sub_payment = $installment->payments->sum('paid_amount');
                    $last_inovoice = DireFeesInstallmentChildPayment::where('school_id',auth()->user()->school_id)->max('invoice_no');

                    $new_subPayment = new DireFeesInstallmentChildPayment();
                    $new_subPayment->direct_fees_installment_assign_id = $installment->id;
                    $new_subPayment->invoice_no = ( $last_inovoice +1 ) ?? 1;
                    $new_subPayment->amount = $paid_amount;
                    $new_subPayment->paid_amount = $paid_amount;
                    $new_subPayment->payment_date = $newformat;
                    $new_subPayment->payment_mode =  $data['method'];
                    $new_subPayment->active_status = 1;
                    $new_subPayment->discount_amount = 0;
                    $new_subPayment->fees_type_id =  $installment->fees_type_id;
                    $new_subPayment->student_id = $installment->student_id;
                    $new_subPayment->record_id = $installment->record_id;
                    $new_subPayment->created_by = Auth::user()->id;
                    $new_subPayment->updated_by =  Auth::user()->id;
                    $new_subPayment->school_id = Auth::user()->school_id;
                    $new_subPayment->balance_amount = ( $payable_amount - ($sub_payment + $paid_amount) ); 
                    $new_subPayment->save();
                    $fees_payment->installment_payment_id = $new_subPayment->id;
                   
                   if(($sub_payment + $paid_amount) == $payable_amount){
                       $installment->active_status = 1;
                   }else{
                       $installment->active_status = 2;
                   }
                   $installment->paid_amount = $sub_payment + $paid_amount;
                   $installment->save();

                   $income_head= generalSetting();
       
                   $add_income = new SmAddIncome();
                   $add_income->name = 'Fees Collect';
                   $add_income->date = date('Y-m-d');
                   $add_income->amount = $fees_payment->amount;
                   $add_income->fees_collection_id = $fees_payment->id;
                   $add_income->active_status = 1;
                   $add_income->income_head_id = $income_head->income_head_id;
                   $add_income->payment_method_id = $payment_setting->id;
                   $add_income->created_by = Auth()->user()->id;
                   $add_income->school_id = Auth::user()->school_id;
                   $add_income->academic_id = getAcademicId();
                   $add_income->save();
                   $after_paid -= ( $paid_amount);
                   Cache::forget('have_due_fees_'.@$fees_payment->studentInfo->user_id); 
                }
            }

            if(Auth::user()->role_id == 2){
                return redirect()->to(url('student-fees'));
            }else{
                return redirect()->to(url('parent-fees'.'/'.$installment->student_id));
            }
       } 

        elseif($data['type'] == "Wallet"){
            $addPayment = new WalletTransaction();
            $addPayment->amount= $data['amount'];
            $addPayment->payment_method= $data['payment_method'];
            $addPayment->user_id= $data['user_id'];
            $addPayment->type= $data['wallet_type'];
            $addPayment->status = 'approve';
            $addPayment->school_id= Auth::user()->school_id;
            $addPayment->academic_id= getAcademicId();
            $result = $addPayment->save();
                if($result){
                    $user = User::find($addPayment->user_id);
                    $currentBalance = $user->wallet_balance;
                    $user->wallet_balance = $currentBalance + $data['amount'];
                    $user->update();
                    $gs = generalSetting();
                    $compact['full_name'] =  $user->full_name;
                    $compact['method'] =  $addPayment->payment_method;
                    $compact['create_date'] =  date('Y-m-d');
                    $compact['school_name'] =  $gs->school_name;
                    $compact['current_balance'] =  $user->wallet_balance;
                    $compact['add_balance'] =  $data['amount'];

                    @send_mail($user->email, $user->full_name, "wallet_approve", $compact);
                }
        }
         
        elseif($data['type'] == "Fees"){
            $extendedController = new FeesExtendedController();
            $extendedController->addFeesAmount($data['transcationId'], null);
        }

        elseif($data['type'] == "Lms"){
           
            $coursePurchase = CoursePurchaseLog::find($data['purchase_log_id']);
            $coursePurchase->active_status = 'approve';
            $coursePurchase->save();
            CoursePurchaseLog::where('course_id',$coursePurchase->course_id)->where('student_id',$coursePurchase->student_id)->where('active_status','pending')->where('payment_method','Stripe')->delete();
            @lmsProfit($coursePurchase->instructor_id, $coursePurchase->amount);
            @addIncome($data['payment_method'], 'Lms Fees Collect', $data['amount'],$coursePurchase->id, Auth()->user()->id);
            return route('lms.student.purchaseLog',$coursePurchase->student_id);
        }

        elseif($data['type'] == "direct_fees"){
            $sub_payment_id = $data['sub_payment_id'];
            $installment_id = $data['installment_id'];
            $sub_payment = DireFeesInstallmentChildPayment::find($sub_payment_id);
            $installment = DirectFeesInstallmentAssign::find($installment_id);
            $payable_amount =  discountFees($installment->id);
            $all_sub_payment = $installment->payments->sum('paid_amount');
            $direct_payment =  $installment->paid_amount;
            $total_paid =  $all_sub_payment + $direct_payment;
            $sub_payment->active_status = 1;
            $sub_payment->balance_amount = ( $payable_amount - ($all_sub_payment + $sub_payment->amount) ); 
            $result = $sub_payment->save();
            if($result && $installment){
                $fees_payment = new SmFeesPayment();
                $fees_payment->student_id = $installment->student_id;
                $fees_payment->amount = $sub_payment->amount;
                $fees_payment->payment_date = date('Y-m-d', strtotime($sub_payment->payment_date));
                $fees_payment->payment_mode = $sub_payment->payment_mode;
                $fees_payment->created_by = Auth::user()->id;
                $fees_payment->school_id = Auth::user()->school_id;
                $fees_payment->record_id = $sub_payment->record_id;
                $fees_payment->academic_id = getAcademicid();
                $fees_payment->installment_payment_id = $sub_payment->id;
                if(($all_sub_payment + $sub_payment->amount) == $payable_amount){
                    $installment->active_status = 1;
                }else{
                    $installment->active_status = 2;
                }
                $installment->paid_amount = $all_sub_payment + $sub_payment->amount;
                $installment->save();

                $income_head= generalSetting();
       
                $add_income = new SmAddIncome();
                $add_income->name = 'Fees Collect';
                $add_income->date = date('Y-m-d');
                $add_income->amount = $fees_payment->amount;
                $add_income->fees_collection_id = $fees_payment->id;
                $add_income->active_status = 1;
                $add_income->income_head_id = $income_head->income_head_id;
                $add_income->payment_method_id = @$payment_setting->id;
                $add_income->created_by = Auth()->user()->id;
                $add_income->school_id = Auth::user()->school_id;
                $add_income->academic_id = getAcademicId();
                $add_income->save();
                $fees_payment->save();
                if(Auth::user()->role_id == 2){
                    return redirect()->to(url('student-fees'));
                }else{
                    return redirect()->to(url('parent-fees'.'/'.$installment->student_id));
                }

        }

        Toastr::success('Operation successful', 'Success');
    }
    }
}
