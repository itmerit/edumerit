<?php 
namespace App\PaymentGateway;

use App\User;
use App\SmStudent;
use App\SmAddIncome;
use App\SmFeesAssign;
use App\SmFeesPayment;
use App\Models\StudentRecord;
use App\Traits\CcAveuneTrait;
use App\SmPaymentGatewaySetting;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Models\DirectFeesInstallmentAssign;
use Modules\Fees\Entities\FmFeesTransaction;
use Modules\Wallet\Entities\WalletTransaction;
use App\Models\DireFeesInstallmentChildPayment;
use Modules\Wallet\Http\Controllers\WalletController;
use Modules\Fees\Http\Controllers\FeesExtendedController;

class CcAveunePayment{
    use CcAveuneTrait;
    public function handle($data)
    {
        try{
            $cc_aveune = SmPaymentGatewaySetting::where('gateway_name', '=', 'CcAveune')
                                ->where('school_id',auth()->user()->school_id)
                                ->select('cca_working_key', 'cca_merchant_id','cca_access_code')
                                ->first();

            if(!$cc_aveune || !$cc_aveune->cca_working_key || !$cc_aveune->cca_merchant_id || !$cc_aveune->cca_access_code){
                Toastr::warning('CcAeune Credentials Can Not Be Blank', 'Warning');
                return redirect()->send()->back();
            }


            $amount = $data['amount'];
            if(array_key_exists('service_charge', $data)) {
                $amount = $data['amount'] + $data['service_charge'];
            }

            $merchant_data='';
            $working_key= $cc_aveune->cca_working_key;//Shared by CCAVENUES
            $access_code= $cc_aveune->cca_access_code ;//Shared by CCAVENUES
   



        }catch(\Exception $e){
            return response()->json(['message'=>$e]);
        }
    }


    public function successCallback($paymentResponse)
    {
      try {
            $cc_aveune = SmPaymentGatewaySetting::where('gateway_name', '=', 'CcAveune')
                                ->where('school_id',auth()->user()->school_id)
                                ->select('cca_working_key', 'cca_merchant_id','cca_access_code')
                                ->first();

            $workingKey= @$cc_aveune->cca_working_key;		//Working Key should be provided here.
            $encResponse= $_POST["encResp"];			//This is the response sent by the CCAvenue Server
            $rcvdString= $this->decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
            $order_status="";
            $decryptValues= explode('&', $rcvdString);
            $dataSize= sizeof($decryptValues);

            for($i = 0; $i < $dataSize; $i++) 
                {
                    $information = explode('=',$decryptValues[$i]);
                    if($i==3)	$order_status = $information[1];
                }
            if($order_status==="Success")
                {
                   $order = $information['order_id'];
                   $get_array = explode("_",$order);
                   $payment_id = $get_array[1];
                   $fees_payment = SmFeesPayment::find($payment_id);
                   $payment_type = $get_array[0];

                if( $payment_type = "direct_fees_total"){
                        $request_amount = $get_array[2];
                        $record_id = $get_array[1];
                        $student_record = StudentRecord::find($record_id);
                        $student_id =  @$student_record->student_id;
                        $after_paid = $request_amount;

                        $installments = DirectFeesInstallmentAssign::where('record_id', $record_id)->get();
                        $total_paid = $installments->sum('paid_amount');
                        $total_amount = $installments->sum('amount');
                        $total_discount = $installments->sum('discount_amount');
                        $balace_amount = $total_amount - ($total_discount +  $total_paid);
                        if($balace_amount <  $request_amount){
                                Toastr::error('Amount is greater than due', 'Failed');
                                if(Auth::user()->role_id == 2){
                                    return redirect()->to(url('student-fees'));
                                }else{
                                    return redirect()->to(url('parent-fees'.'/'.$student_id));
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
                            $fees_payment->amount = $paid_amount;
                            $fees_payment->payment_date = date('Y-m-d');
                            $fees_payment->payment_mode =  "CcAveune";
                            $fees_payment->created_by = Auth::id();
                            $fees_payment->school_id = Auth::user()->school_id;
                            $fees_payment->record_id = $installment->record_id;
                            $fees_payment->academic_id = getAcademicid();
                            $fees_payment->direct_fees_installment_assign_id = $installment->id;
                        
                            $payment_mode_name= "CcAveune";
                            $payment_method= SmPaymentMethhod::where('method',$payment_mode_name)->first();
                            $installment = DirectFeesInstallmentAssign::find($installment->id);
                            $installment->payment_date =  $newformat;
                            $installment->payment_mode =  "CcAveune";
                                
            
                            $payable_amount =  discountFees($installment->id);
                            $sub_payment = $installment->payments->sum('paid_amount');
                            $last_inovoice = DireFeesInstallmentChildPayment::where('school_id',auth()->user()->school_id)->max('invoice_no');
        
                            $new_subPayment = new DireFeesInstallmentChildPayment();
                            $new_subPayment->direct_fees_installment_assign_id = $installment->id;
                            $new_subPayment->invoice_no = ( $last_inovoice +1 ) ?? 1;
                            $new_subPayment->amount = $paid_amount;
                            $new_subPayment->paid_amount = $paid_amount;
                            $new_subPayment->payment_date = $newformat;
                            $new_subPayment->payment_mode =   "CcAveune";
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
                            $add_income->payment_method_id = $payment_method->id;
                            $add_income->created_by = Auth()->user()->id;
                            $add_income->school_id = Auth::user()->school_id;
                            $add_income->academic_id = getAcademicId();
                            $add_income->save();
                            $after_paid -= ( $paid_amount);
                        }
                        if(Auth::user()->role_id == 2){
                            return redirect()->to(url('student-fees'));
                        }else{
                            return redirect()->to(url('parent-fees'.'/'.$installment->student_id));
                        }
                           
                    }
                }elseif($payment_type = "direct_fees"){
                    $sub_payment_id = $get_array[1];
                    $sub_payment = DireFeesInstallmentChildPayment::find($sub_payment_id);
                    $installment_id = $sub_payment->direct_fees_installment_assign_id ;
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
                        $fees_payment->save();

                        if(Auth::user()->role_id == 2){
                            return redirect()->to(url('student-fees'));
                        }else{
                            return redirect()->to(url('parent-fees'.'/'.$installment->student_id));
                        }
                    }
               
                    if($fees_payment){
                            $fees_payment->active_status = 1;
                            $result = $fees_payment->save();
                    }
                    if($result){
                            if($payment_type =="oldFees"){
                                $fees_assign = SmFeesAssign::find($fees_payment->assign_id)->first();
                                $fees_assign->fees_amount -= floatval($fees_payment->amount);
                                $fees_assign->save();
                            }
                        
                            $income_head = generalSetting();
                            $add_income = new SmAddIncome();
                            $add_income->name = 'Fees Collect';
                            $add_income->date = date('Y-m-d', strtotime(date('Y-m-d')));
                            $add_income->amount = $fees_payment->amount;
                            $add_income->fees_collection_id = $fees_payment->id;
                            $add_income->active_status = 1;
                            $add_income->income_head_id = $income_head->income_head_id;
                            $add_income->payment_method_id = $cc_aveune->id;
                            $add_income->created_by = Auth()->user()->id;
                            $add_income->school_id = Auth::user()->school_id;
                            $add_income->academic_id = getAcademicId();
                            $add_income->save();

                    }
                }elseif($get_array[0] = "Fees"){
                    $transcation= FmFeesTransaction::find($get_array[1]);
                    $extendedController = new FeesExtendedController();
                    $extendedController->addFeesAmount($get_array[1], null);
                    return redirect()->to(url('fees/student-fees-list',$transcation->student_id));
                }elseif($get_array[0] = "Wallet"){
                    $transaction = WalletTransaction::find($get_array[1]);
                    $transaction->status = "approve";
                    $transaction->updated_at = date('Y-m-d');
                    $result = $transaction->update();
                    if($result){
                        $user = User::find($transaction->user_id);
                        $currentBalance = $user->wallet_balance;
                        $user->wallet_balance = $currentBalance + $transaction->amount;
                        $user->update();
                        $gs = generalSetting();
                        $compact['full_name'] =  $user->full_name;
                        $compact['method'] =  $transaction->payment_method;
                        $compact['create_date'] =  date('Y-m-d');
                        $compact['school_name'] =  $gs->school_name;
                        $compact['current_balance'] =  $user->wallet_balance;
                        $compact['add_balance'] =  $transaction->amount;

                        @send_mail($user->email, $user->full_name, "wallet_approve", $compact);
                    }
                    return redirect()->route('wallet.my-wallet');
                }
                }
        }
        catch(\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Transaction is Invalid');
            return response()->json(['target_url'=>route('fees.student-fees-list', $paymentResponse['studentId'])]);
        }
    }

    public function fail($paymentResponse)
    {   
        try {
            Toastr::error('Transaction is Invalid');
            return redirect()->back();

        }
        catch(\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Transaction is Invalid');
            return response()->json(['target_url'=>route('fees.student-fees-list', $paymentResponse['studentId'])]);
        }
    }
}
