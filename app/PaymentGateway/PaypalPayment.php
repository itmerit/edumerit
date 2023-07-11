<?php 
namespace App\PaymentGateway;

use App\User;
use Exception;
use App\SmSchool;
use App\SmAddIncome;
use Omnipay\Omnipay;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use App\SmFeesPayment;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use App\SmPaymentMethhod;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use PayPal\Api\RedirectUrls;
use App\SmPaymentGatewaySetting;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\DirectFeesInstallmentAssign;
use Modules\Lms\Entities\CoursePurchaseLog;
use Modules\Fees\Entities\FmFeesTransaction;
use Illuminate\Validation\ValidationException;
use Modules\Wallet\Entities\WalletTransaction;
use App\Models\DireFeesInstallmentChildPayment;
use Modules\Saas\Entities\SmSubscriptionPayment;
use Modules\Fees\Http\Controllers\FeesExtendedController;

class PaypalPayment{
    private $_api_context;
    private $mode;
    private $client_id;
    private $secret;

    public function __construct()
    {
        $paypalDetails = SmPaymentGatewaySetting::where('school_id',auth()->user()->school_id)
                        ->select('gateway_username', 'gateway_password', 'gateway_signature', 'gateway_client_id', 'gateway_secret_key', 'gateway_mode')
                        ->where('gateway_name', '=', 'PayPal')
                        ->first();

        if(!$paypalDetails || !$paypalDetails->gateway_secret_key){
            Toastr::warning('Paypal Credentials Can Not Be Blank', 'Warning');
            return redirect()->send()->back();
        }
        $this->_api_context = Omnipay::create('PayPal_Rest');
        $this->_api_context->setClientId($paypalDetails->gateway_client_id);
        $this->_api_context->setSecret($paypalDetails->gateway_secret_key);
        $this->_api_context->setTestMode(strtolower($paypalDetails->gateway_mode) !== 'live');      

    }

    public function handle($data)
    {
        $response = $this->_api_context->purchase(array(
                'amount' => $data['amount'] + gv($data, 'service_charge', 0),
                'currency' => generalSetting()->currency,
                'returnUrl' => URL::to('payment_gateway_success_callback','PayPal'),
                'cancelUrl' => URL::to('payment_gateway_cancel_callback','PayPal'),

            ))->send();
           
            $payment_id = gv($response->getData(), 'id');
            if(!$payment_id){
                throw ValidationException::withMessages(['amount'=> $response->getMessage()]);
            }
            if ($data['type'] == "Wallet") {
                $addPayment = new WalletTransaction();
                $addPayment->amount= $data['amount'];
                $addPayment->payment_method= $data['payment_method'];
                $addPayment->user_id= $data['user_id'];
                $addPayment->type= $data['wallet_type'];
                $addPayment->school_id= auth()->user()->school_id;
                $addPayment->academic_id= getAcademicId();
                $addPayment->save();
                Session::put('paypal_payment_id', $payment_id);
                Session::put('payment_type', $data['type']);
                Session::put('wallet_payment_id',  $addPayment->id);
            }
            elseif($data['type'] == "direct_fees"){
                Session::put('payment_type', $data['type']);
                Session::put('sub_payment_id', $data['sub_payment_id']);
                Session::put('installment_id', $data['installment_id']);
            }
            elseif($data['type'] == "direct_fees_total"){
                Session::put('payment_type', $data['type']);
                Session::put('record_id', $data['record_id']);
                Session::put('student_id', $data['student_id']);
                Session::put('request_amount', $data['request_amount']);
            }
            
            elseif($data['type'] == "Lms"){
                  Session::put('payment_type', $data['type']);
                if(array_key_exists('purchase_log_id',$data)){
                    Session::put('purchase_log_id', $data['purchase_log_id']);
                }
            }elseif($data['type'] == "old_fees"){
                Session::put('payment_type', $data['type']);
                Session::put('fees_payment_id', $data['fees_payment_id']);
            }elseif($data['type'] == 'saas_school_reg'){
                Session::put('payment_type', $data['type']);
                Session::put('subs_payment_id', $data['subs_payment_id']);
            }
            
            else{
                Session::forget('amount'); 
                if(array_key_exists('purchase_log_id',$data)){
                    Session::put('purchase_log_id', $data['purchase_log_id']);
                }
                Session::put('payment_type', $data['type']);
                Session::put('invoice_id', gv('invoice_id', $data));
                Session::put('amount', $data['amount']);
                Session::put('payment_method',  $data['payment_method']);
                Session::put('transcation_id',  gv( $data, 'transcationId'));
                Session::put('paypal_payment_id', $payment_id);
                Session::put('fees_payment_id',  gv($data, 'transcationId' ));
            }
            
            if ($response->isRedirect()) {
                if(request()->wantsJson()){
                    return $response->getRedirectUrl();
                }else{
                    return redirect()->to($response->getRedirectUrl())->send();
                }
            } else {
                throw ValidationException::withMessages(['amount'=> $response->getMessage()]);
            }
    }


    public function successCallback()
    {
        $request = App::make(Request::class);
        $payment_method = SmPaymentMethhod::where('method',"PayPal")->first();
      try {
            $payment_id = Session::get('paypal_payment_id');
            Session::forget('paypal_payment_id');
            if (empty($request->input('paymentId')) || empty($request->input('PayerID'))) {
                Session::put('error','Payment failed');
                return Redirect::route('paywithpaypal');
            }
          $transaction = $this->_api_context->completePurchase(array(
              'payer_id' => request()->input('PayerID'),
              'transactionReference' => request()->input('paymentId'),
          ));
          $response = $transaction->send();


            if ($response->isSuccessful() && $response->getData()['state'] == 'approved') {
                $paypal_wallet_paymentId = Session::get('wallet_payment_id');

                if(Session::get('payment_type')== "Wallet" && !is_null($payment_id)){
                    $transaction = WalletTransaction::find($paypal_wallet_paymentId);
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

                }elseif(Session::get('payment_type') == "Fees" && !is_null(Session::get('fees_payment_id'))){
                    
                    $transcation= FmFeesTransaction::find(Session::get('fees_payment_id'));
                    $extendedController = new FeesExtendedController();
                    $extendedController->addFeesAmount(Session::get('fees_payment_id'), null);
                    
                    Session::put('success', 'Payment success');
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->to(url('fees/student-fees-list',$transcation->student_id));
                }
                elseif(Session::get('payment_type') == "Lms"){
                    
                    $coursePurchase = CoursePurchaseLog::find(Session::get('purchase_log_id'));
                    $coursePurchase->active_status= 'approve';
                    $coursePurchase->save();
                    @lmsProfit($coursePurchase->instructor_id, $coursePurchase->amount);
                    @addIncome($payment_method, 'Lms Fees Collect', Session::get('amount'), Session::get('purchase_log_id'), Auth()->user()->id);
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->to(url('lms/student/purchase-log',$coursePurchase->student_id));
                }

                elseif(Session::get('payment_type')== "direct_fees" && Session::get('sub_payment_id')){

                    $sub_payment_id = Session::get('sub_payment_id');
                    $installment_id = Session::get('installment_id');
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
                        $fees_payment->save();
                        Cache::forget('have_due_fees_'.@$fees_payment->studentInfo->user_id); 
                        if(Auth::user()->role_id == 2){
                            return redirect()->to(url('student-fees'));
                        }else{
                            return redirect()->to(url('parent-fees'.'/'.$installment->student_id));
                        }
                        
                    }

                }
                elseif(Session::get('payment_type') ==  "direct_fees_total")
                {
                    $request_amount = Session::get('request_amount');
                    $record_id = Session::get('record_id');
                    $student_id = Session::get('student_id');
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
                           $fees_payment->fees_discount_id = !empty($request->fees_discount_id) ? $request->fees_discount_id : "";
                           $fees_payment->discount_amount = !empty($request->applied_amount) ? $request->applied_amount : 0;
                           $fees_payment->amount = $paid_amount;
                           $fees_payment->payment_date = date('Y-m-d');
                           $fees_payment->payment_mode =  "PayPal";;
                           $fees_payment->created_by = Auth::id();
                           $fees_payment->school_id = Auth::user()->school_id;
                           $fees_payment->record_id = $installment->record_id;
                           $fees_payment->academic_id = getAcademicid();
                           $fees_payment->direct_fees_installment_assign_id = $installment->id;
                       
                            $payment_mode_name= "PayPal";
                            $payment_method= SmPaymentMethhod::where('method',$payment_mode_name)->first();
                            $installment = DirectFeesInstallmentAssign::find($installment->id);
                            $installment->payment_date =  $newformat;
                            $installment->payment_mode =  "PayPal";
                            
        
                            $payable_amount =  discountFees($installment->id);
                            $sub_payment = $installment->payments->sum('paid_amount');
                            $last_inovoice = DireFeesInstallmentChildPayment::where('school_id',auth()->user()->school_id)->max('invoice_no');
        
                            $new_subPayment = new DireFeesInstallmentChildPayment();
                            $new_subPayment->direct_fees_installment_assign_id = $installment->id;
                            $new_subPayment->invoice_no = ( $last_inovoice +1 ) ?? 1;
                            $new_subPayment->amount = $paid_amount;
                            $new_subPayment->paid_amount = $paid_amount;
                            $new_subPayment->payment_date = $newformat;
                            $new_subPayment->payment_mode =   "PayPal";
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
                           Cache::forget('have_due_fees_'.@$fees_payment->studentInfo->user_id); 
                        }
                    }
   
                    if(Auth::user()->role_id == 2){
                        return redirect()->to(url('student-fees'));
                    }else{
                        return redirect()->to(url('parent-fees'.'/'.$installment->student_id));
                    }
                } 
                elseif((Session::get('payment_type') ==  "old_fees" ) && Session::get('fees_payment_id')){
                    $fees_payment = SmFeesPayment::find(Session::get('fees_payment_id'));
                    $fees_payment->active_status = 1;
                    $fees_payment->save();
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
                    Cache::forget('have_due_fees_'.@$fees_payment->studentInfo->user_id); 
                    if(Auth::user()->role_id == 2){
                        return redirect()->to(url('student-fees'));
                    }else{
                        return redirect()->to(url('parent-fees'.'/'. $fees_payment->student_id));
                    }
                }
                elseif((Session::get('payment_type') ==  "saas_school_reg" ) && Session::get('subs_payment_id')){
                    $payment = SmSubscriptionPayment::find(Session::get('subs_payment_id'));
                    $payment->payment_type = 'paid';
                    $payment->approve_status = 'approved';
                    $payment->payment_date = date('Y-m-d');
                    $payment->save();
                    Toastr::success('Payment Successfully Complete', 'Success');
                    $school = SmSchool::find($payment->school_id);
                    return redirect('//' . $school->domain . '.' . config('app.short_url') . '/home');
                }
                
                else{
                    Toastr::error('Operation Failed paypal', 'Failed');
                    return redirect()->back();
                }
            }
        }catch(\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function cancelCallback(){
        
        Toastr::error('Operation Failed', 'Failed');
        if (Session::get('payment_type') == "Wallet") {
            return redirect()->route('wallet.my-wallet');
        } elseif (Session::get('payment_type') == "Fees") {
            $transaction = FmFeesTransaction::find(Session::get('fees_payment_id'));
            if ($transaction) {
                return redirect()->to(url('fees/student-fees-list', $transaction->student_id));
            } else {
                return redirect()->route('admin-dashboard');
            }
        } else {
            Toastr::error('Operation Failed paypal', 'Failed');
            return redirect()->route('admin-dashboard');
        }
    }
}
