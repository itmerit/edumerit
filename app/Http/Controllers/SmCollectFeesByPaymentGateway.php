<?php

namespace App\Http\Controllers;

use Stripe;
use App\YearCheck;
use App\SmFeesType;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use App\SmFeesPayment;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use App\SmGeneralSettings;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use PayPal\Api\RedirectUrls;
use App\SmFeesAssignDiscount;
use App\SmPaymentGatewaySetting;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Facades\URL;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\DirectFeesInstallmentAssign;
use App\Models\DireFeesInstallmentChildPayment;
use Modules\University\Entities\UnFeesInstallmentAssign;

class SmCollectFeesByPaymentGateway extends Controller
{

    public function collectFeesByGateway($amount, $student_id, $type)
    {
        try {
            $amount = $amount;
            $fees_type_id = $type;
            $student_id = $student_id;
            $discounts = SmFeesAssignDiscount::where('student_id', $student_id)->get();

            $applied_discount = [];
            foreach ($discounts as $fees_discount) {
                $fees_payment = SmFeesPayment::select('fees_discount_id')->where('fees_discount_id', $fees_discount->id)->first();
                if (isset($fees_payment->fees_discount_id)) {
                    $applied_discount[] = $fees_payment->fees_discount_id;
                }
            }
            return view('backEnd.feesCollection.collectFeesByGateway', compact('amount', 'discounts', 'fees_type_id', 'student_id', 'applied_discount'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function payByPaypal(Request $request)
    { 
        try{
            if(moduleStatusCheck('University')){ 
                $installment = UnFeesInstallmentAssign::find($request->installment_id);
                $description = $installment->installment->title ?? 'Fees Payment' ;
              }elseif(directFees()){
                  $installment = DirectFeesInstallmentAssign::find($request->installment_id);
                  $description = $installment->installment->title ?? 'Fees Payment' ;
              }
    
            $user = Auth::user();
            $fees_payment = new SmFeesPayment();
            $fees_payment->student_id = $request->student_id;
            $fees_payment->amount = $request->real_amount;
            $fees_payment->assign_id = $request->assign_id;
            $fees_payment->payment_date = date('Y-m-d');
            $fees_payment->payment_mode = 'PayPal';
            $fees_payment->created_by = $user->id;
            $fees_payment->record_id = $request->record_id;
            $fees_payment->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $fees_payment->un_academic_id = getAcademicId();
                $fees_payment->un_fees_installment_id  = $request->installment_id;
                $fees_payment->un_semester_label_id = $request->un_semester_label_id;
            }
            elseif(directFees()){
                $fees_payment->direct_fees_installment_assign_id = $installment->id;
                $fees_payment->academic_id = getAcademicId();
            }
            else{
            $fees_payment->fees_type_id = $request->fees_type_id;
            $fees_payment->academic_id = getAcademicId();
            }
           
            $fees_payment->active_status = 0;
            $fees_payment->save();
    
            $data = [];
            $data['payment_method'] = "PayPal";
            $data['amount'] = $request->real_amount;
            $data['service_charge'] = chargeAmount("PayPal", $request->real_amount);
            $data['fees_payment_id'] = $fees_payment->id;
            $data['type'] = "old_fees";
            $classMap = config('paymentGateway.' . $data['payment_method']);
            $make_payment = new $classMap();
            $url = $make_payment->handle($data);
        }
         catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getPaymentStatus(Request $request)
    {
        
        $paypal_fees_paymentId = Session::get('paypal_fees_paymentId');
        $fees_payment = null;
        $url = route('login');
        
        if (!is_null($paypal_fees_paymentId)) {
            $fees_payment = SmFeesPayment::find($paypal_fees_paymentId);
        }

        if (auth()->check()) {
            $role_id = auth()->user()->role_id;
            if ($role_id == 3 && $fees_payment) {
                $url = route('parent_fees', $fees_payment->student_id);
            } else if ($role_id == 2) {
                $url = route('student_fees'); 
            } else {
                $url = route('dashboard');
            }
        }

        try {
            $payment_id = Session::get('paypal_payment_id');
            Session::forget('paypal_payment_id');
            if (empty($request->input('PayerID')) || empty($request->input('token'))) {
                \Session::put('error', 'Payment failed');
                return redirect($url);
            }
            $payment = Payment::get($payment_id, $this->_api_context);
        
            $execution = new PaymentExecution();
            $execution->setPayerId($request->input('PayerID'));
            $result = $payment->execute($execution, $this->_api_context);
           
            if ($result->getState() == 'approved' && $fees_payment) {
                $fees_payment->active_status = 1;
                $fees_payment->save();
                if(moduleStatusCheck('University')){
                    $installment = UnFeesInstallmentAssign::find($fees_payment->un_fees_installment_id);
                    $installment->paid_amount = discountFeesAmount($installment->id);
                    $installment->active_status = 1;
                    $installment->payment_mode = 'Paypal';
                    $installment->payment_date = $fees_payment->payment_date;
                    $installment->save();
                    Session::put('success', 'Payment success');
                    Toastr::success('Operation successful', 'Success');
                }
                }
                elseif(directFees()){

                DirectFeesInstallmentAssign::find( Session::get('installment_id'));
                $installment = DirectFeesInstallmentAssign::find( Session::get('installment_id'));
               
                $installment->paid_amount = discountFees($installment->id);
                $installment->active_status = 1;
                $installment->payment_mode = $fees_payment->payment_mode;
                $installment->payment_date = $fees_payment->payment_date;
                $installment->save();

                $payable_amount =  discountFees($installment->id);
                $sub_payment = $installment->payments->sum('paid_amount');
                $direct_payment =  $installment->paid_amount;
                $total_paid =  $sub_payment + $direct_payment;

                $last_inovoice = DireFeesInstallmentChildPayment::where('school_id',auth()->user()->school_id)->max('invoice_no');
                $new_subPayment = new DireFeesInstallmentChildPayment();
                $new_subPayment->direct_fees_installment_assign_id = $installment->id;
                $new_subPayment->invoice_no = ( $last_inovoice +1 ) ?? 1;
                $new_subPayment->direct_fees_installment_assign_id = $installment->id;
                $new_subPayment->amount =  $installment->paid_amount;
                $new_subPayment->paid_amount =  $installment->paid_amount;
                $new_subPayment->payment_date = $fees_payment->payment_date;
                $new_subPayment->payment_mode =  $fees_payment->payment_mode;
                $new_subPayment->note = $fees_payment->note;
                $new_subPayment->slip = $fees_payment->slip;
                $new_subPayment->active_status = 1;
                $new_subPayment->discount_amount = 0;
                $new_subPayment->fees_type_id =  $installment->fees_type_id;
                $new_subPayment->student_id = $fees_payment->student_id;
                $new_subPayment->record_id = $fees_payment->record_id;
                
                $new_subPayment->created_by = Auth::user()->id;
                $new_subPayment->updated_by =  Auth::user()->id;
                $new_subPayment->school_id = Auth::user()->school_id;
                $new_subPayment->balance_amount = ( $payable_amount - ($sub_payment + $request->amount) ); 
                $new_subPayment->save();
                
                Session::put('success', 'Payment success');
                Toastr::success('Operation successful', 'Success');
            } else {
                Toastr::error('Operation Failed', 'Failed');
            }

            return redirect($url);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect($url);
        }
    }


    public function collectFeesStripe($amount, $student_id, $type)
    {
        try {
            $amount = $amount;
            $fees_type_id = $type;
            $student_id = $student_id;
            $discounts = SmFeesAssignDiscount::where('student_id', $student_id)->get();
            $stripe_publisher_key = SmPaymentGatewaySetting::where('gateway_name', '=', 'Stripe')->first()->stripe_publisher_key;

            $applied_discount = SmFeesPayment::select('fees_discount_id')->whereIn('fees_discount_id', $discounts->pluck('id')->toArray())->pluck('fees_discount_id')->toArray();

            return view('backEnd.feesCollection.collectFeesStripeView', compact('amount', 'discounts', 'fees_type_id', 'student_id', 'applied_discount', 'stripe_publisher_key'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function stripeStore(Request $request)
    {
        try {
            $system_currency = '';
            $currency_details = SmGeneralSettings::select('currency')->where('id', 1)->first();
            if (isset($currency_details)) {
                $system_currency = $currency_details->currency;
            }
            $stripeDetails = SmPaymentGatewaySetting::select('stripe_api_secret_key', 'stripe_publisher_key')->where('gateway_name', '=', 'Stripe')->first();

            Stripe\Stripe::setApiKey($stripeDetails->stripe_api_secret_key);
            $charge = Stripe\Charge::create([
                "amount" => $request->real_amount * 100,
                "currency" => $system_currency,
                "source" => $request->stripeToken,
                "description" => "Student Fees payment"
            ]);
            if ($charge) {
                $user = Auth::user();
                $fees_payment = new SmFeesPayment();
                $fees_payment->student_id = $request->student_id;
                $fees_payment->fees_type_id = $request->fees_type_id;
                $fees_payment->amount = $request->real_amount;
                $fees_payment->payment_date = date('Y-m-d');
                $fees_payment->payment_mode = 'Stripe';
                $fees_payment->created_by = $user->id;
                $fees_payment->school_id = Auth::user()->school_id;
                $fees_payment->save();

                Toastr::success('Operation successful', 'Success');
                return redirect('student-fees');

            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect('student-fees');

            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
