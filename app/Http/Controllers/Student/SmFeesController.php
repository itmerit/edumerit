<?php

namespace App\Http\Controllers\Student;

use Stripe;
use App\User;
use App\SmStudent;
use Stripe\Charge;
use App\SmAddIncome;
use App\SmFeesAssign;
use App\SmFeesMaster;
use App\SmBankAccount;
use App\SmFeesPayment;
use App\SmPaymentMethhod;
use App\SmBankPaymentSlip;
use App\SmGeneralSettings;
use App\Models\FeesInvoice;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmFeesAssignDiscount;
use App\Traits\CcAveuneTrait;
use App\SmPaymentGatewaySetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Models\DirectFeesInstallment;
use Unicodeveloper\Paystack\Paystack;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Models\DirectFeesInstallmentAssign;
use App\Models\DireFeesInstallmentChildPayment;
use Modules\University\Entities\UnFeesInstallmentAssign;
use Modules\University\Entities\UnFeesInstallAssignChildPayment;

class SmFeesController extends Controller
{
    public $paystack;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
        $this->paystack = new Paystack();
    }


    use CcAveuneTrait;
    public function studentFees()
    {
        try {
            $student = Auth::user()->student->load('feesAssignDiscount');
            $payment_gateway = SmPaymentMethhod::first();
            $records = studentRecords(null, $student->id)->with('fees.feesGroupMaster', 'class','section','directFeesInstallments.installment','directFeesInstallments.payments.user')->get();
            $fees_discounts = $student->feesAssignDiscount;
            $applied_discount = [];
            foreach ($fees_discounts as $fees_discount) {
                $fees_payment = SmFeesPayment::select('fees_discount_id')
                                    ->where('fees_discount_id', $fees_discount->id)
                                    ->whereIn('record_id',$records->pluck('id')->toArray())
                                    ->first();
                if (isset($fees_payment->fees_discount_id)) {
                    $applied_discount[] = $fees_payment->fees_discount_id;
                }
            }

            $paystack_info = DB::table('sm_payment_gateway_settings')->where('gateway_name', 'Paystack')
                                        ->where('school_id', Auth::user()->school_id)->first();

            $account_info = SmPaymentMethhod::whereIn('method', ['Bank','Cheque'])->where('school_id',auth()->user()->school_id)->get();
            $data['bank_info'] = $account_info->where('method', 'Bank')->first();
            $data['cheque_info'] = $account_info->where('method', 'Cheque')->first();
            $gateway = SmPaymentGatewaySetting::where('service_charge', 1)->get(['gateway_name', 'charge_type', 'charge']);
            $method['Stripe'] = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')->where('school_id', Auth::user()->school_id)->first();
            $invoice_settings = FeesInvoice::where('school_id',auth()->user()->school_id)->first();
            $razorpay_info = SmPaymentGatewaySetting::where('gateway_name', 'RazorPay')->where('school_id', Auth::user()->school_id)->first();
            return view('backEnd.studentPanel.fees_pay', compact('student', 'fees_discounts', 'applied_discount', 'payment_gateway', 'paystack_info', 'data','records', 'gateway','method','invoice_settings', 'razorpay_info'));
        } catch (\Exception $e) {         
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function redirectToGateway(Request $request)
    {
        try {
            $paystack_info = DB::table('sm_payment_gateway_settings')->where('gateway_name', 'Paystack')->where('school_id', Auth::user()->school_id)->first();
            $withServiceCharge = ($request->amount/100) + (chargeAmount('Paystack', ($request->amount/100)));
            $withServiceCharge = $withServiceCharge*100;
           
            Config::set('paystack.publicKey', $paystack_info->gateway_publisher_key);
            Config::set('paystack.secretKey', $paystack_info->gateway_secret_key);
            Config::set('paystack.merchantEmail', $paystack_info->gateway_username);

            Session::put('fees_type_id', $request->fees_type_id);
            Session::put('student_id', $request->student_id);
            Session::put('fees_master_id', $request->fees_master_id);
            Session::put('amount', $request->amount);
            Session::put('payment_mode', $request->payment_mode);
            Session::put('assign_id',$request->assign_id);
            Session::put('record_id',$request->record_id);
            Session::put('installment_id',$request->installment_id);
          
            $payStackData= [
                "amount" => intval($withServiceCharge),
                "email" => $request->email,
                "callback_url" => 'payment/callback',
                "currency" => (generalSetting()->currency != ""  ? generalSetting()->currency : "ZAR")
            ];
            $this->paystack = new Paystack();

            return redirect($this->paystack->getAuthorizationResponse($payStackData)['data']['authorization_url']);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**PSm
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        try {
            $user = Auth::User();
            $amount = Session::get('amount');
            $amount = $amount / 100;
            $fees_master_id = Session::get('fees_master_id');
            $fees_payment = new SmFeesPayment();
            $fees_payment->student_id = Session::get('student_id');
            $fees_payment->discount_amount = 0;
            $fees_payment->fine = 0;
            $fees_payment->amount = $amount;
            $fees_payment->assign_id = Session::get('assign_id');
            $fees_payment->payment_date = date('Y-m-d', strtotime(date('Y-m-d')));
            $fees_payment->payment_mode = 'PS';
            $fees_payment->record_id = Session::get('record_id');
            $fees_payment->school_id = Auth::user()->school_id;

            if(moduleStatusCheck('University')){
                $installment = UnFeesInstallmentAssign::find(Session::get('installment_id'));
                if($installment){
                    $fees_payment->un_academic_id = getAcademicId();
                    $fees_payment->un_fees_installment_id  = $installment->installment_id;
                    $fees_payment->un_semester_label_id = $installment->un_semester_label_id;
                    $installment->paid_amount = discountFeesAmount($installment->id);
                    $installment->active_status = 1;
                    $installment->payment_mode = "Paystack";
                    $installment->payment_date = date('Y-m-d', strtotime(date('Y-m-d')));
                    $installment->save();
                }
            }
            else{
                $fees_payment->fees_type_id = Session::get('fees_type_id');
                $fees_payment->academic_id = getAcademicId();
                }
            $result = $fees_payment->save();

            $income_head=generalSetting();

            $add_income = new SmAddIncome();
            $add_income->name = 'Fees Collect';
            $add_income->date = date('Y-m-d', strtotime(date('Y-m-d')));
            $add_income->amount = $amount;
            $add_income->fees_collection_id = $fees_payment->id;
            $add_income->active_status = 1;
            $add_income->income_head_id = $income_head->income_head_id;
            $add_income->payment_method_id = 5;
            $add_income->created_by = Auth()->user()->id;
            $add_income->school_id = Auth::user()->school_id;
            $add_income->academic_id = getAcademicId();
            $add_income->save();

            if(! moduleStatusCheck('University')){
                $get_master_id=SmFeesMaster::join('sm_fees_assigns','sm_fees_assigns.fees_master_id','=','sm_fees_masters.id')
                ->where('sm_fees_masters.fees_type_id',$fees_payment->fees_type_id)
                ->where('sm_fees_assigns.student_id',$fees_payment->student_id)->first();

                $fees_assign=SmFeesAssign::where('fees_master_id',$get_master_id->fees_master_id)->where('student_id',$fees_payment->student_id)->where('school_id',Auth::user()->school_id)->first();
                $fees_assign->fees_amount-=$amount;
                $fees_assign->save();
            }
    

            if ($result) {
                if ($user->role_id == 2) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('student-fees');
                    // return redirect('student-fees')->with('message-success', 'Fees payment has been collected  successfully');
                } else {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('parent-fees/' . Session::get('student_id'));
                    // return redirect('parent-fees/'.Session::get('student_id'))->with('message-success', 'Fees payment has been collected  successfully');
                }
            } else {
                if ($user->role_id == 2) {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect('student-fees');
                    // return redirect('student-fees')->with('message-danger', 'Something went wrong, please try again');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect('student-fees');
                    // return redirect('student-fees')->with('message-danger', 'Something went wrong, please try again');
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesPaymentStripe($fees_type, $student_id, $amount,$assign_id,$record_id)
    {
        $stripe_info = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')->where('school_id', Auth::user()->school_id)->first();
        return view('backEnd.studentPanel.stripe_payment', compact('stripe_info', 'fees_type', 'student_id', 'amount','assign_id','record_id'));
    }

    public function feesPaymentStripeStore(Request $request)
    {
        $payment_setting = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')->where('school_id', Auth::user()->school_id)->first();
        $withServiceCharge = $request->amount +  chargeAmount('Stripe', $request->amount);
        
        Stripe\Stripe::setApiKey($payment_setting->gateway_secret_key);

        Stripe\Charge::create([
            "amount" => $withServiceCharge * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => generalSetting()->school_name
        ]);

        $user = Auth::User();

        // $student = SmStudent::where('user_id', $id)->where('school_id',Auth::user()->school_id)->first();
        $fees_payment = new SmFeesPayment();
        $fees_payment->student_id = $request->student_id;
        $fees_payment->discount_amount = 0;
        $fees_payment->fine = 0;
        $fees_payment->amount = $request->amount;
        $fees_payment->assign_id = $request->assign_id;
        $fees_payment->payment_date = date('Y-m-d', strtotime(date('Y-m-d')));
        $fees_payment->record_id = $request->record_id;
        $fees_payment->payment_mode = 'ST';
        $fees_payment->school_id = Auth::user()->school_id;

        if(moduleStatusCheck('University')){
            $fees_payment->un_academic_id = getAcademicId();
            $fees_payment->un_fees_installment_id  = $request->installment_id;
            $fees_payment->un_semester_label_id = $request->un_semester_label_id;

            $installment = UnFeesInstallmentAssign::find($fees_payment->un_fees_installment_id);
            $installment->paid_amount = discountFeesAmount($installment->id);
            $installment->active_status = 1;
            $installment->payment_mode = "Stripe";
            $installment->payment_date = $fees_payment->payment_date;
            $installment->save();
            
        }elseif(directFees()){
            $installment = DirectFeesInstallmentAssign::find($request->installment_id);
            $installment->paid_amount = $request->amount;
            $installment->active_status = 1;
            $installment->payment_mode = "Stripe";
            $installment->payment_date = $fees_payment->payment_date;
            $installment->save();
        }
        else{
        $fees_payment->fees_type_id = $request->fees_type;
        $fees_payment->academic_id = getAcademicId();
        }
        
        $result = $fees_payment->save();

        $income_head=generalSetting();

        $add_income = new SmAddIncome();
        $add_income->name = 'Fees Collect';
        $add_income->date = date('Y-m-d', strtotime(date('Y-m-d')));
        $add_income->amount = $request->amount;
        $add_income->fees_collection_id = $fees_payment->id;
        $add_income->active_status = 1;
        $add_income->income_head_id = $income_head->income_head_id;
        $add_income->payment_method_id = 4;
        $add_income->created_by = Auth()->user()->id;
        $add_income->school_id = Auth::user()->school_id;
        $add_income->academic_id = getAcademicId();
        $add_income->save();

        if(moduleStatusCheck('University')){

        }elseif(directFees()){

        }
        else{
            $get_master_id=SmFeesMaster::join('sm_fees_assigns','sm_fees_assigns.fees_master_id','=','sm_fees_masters.id')
                ->where('sm_fees_masters.fees_type_id',$request->fees_type)
                ->where('sm_fees_assigns.student_id',$request->student_id)->first();

            $fees_assign=SmFeesAssign::where('fees_master_id',$get_master_id->fees_master_id)->where('student_id',$fees_payment->student_id)->where('school_id',Auth::user()->school_id)->first();
            $fees_assign->fees_amount-=$request->amount;
            $fees_assign->save();
        }

        if ($result) {
            if ($user->role_id == 2) {
                Toastr::success('Operation successful', 'Success');
                return redirect('student-fees');
                // return redirect('student-fees')->with('message-success', 'Fees payment has been collected  successfully');
            } else {
                Toastr::success('Operation successful', 'Success');
                return redirect('parent-fees/' . $request->student_id);
                // return redirect('parent-fees/'.Session::get('student_id'))->with('message-success', 'Fees payment has been collected  successfully');
            }
        } else {
            if ($user->role_id == 2) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect('student-fees');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect('parent-fees/' . $request->student_id);
              
            }
        }
        

    }

    public function feesGenerateModalChild(Request $request, $amount, $student_id, $type,$assign_id, $record_id)
    {
        try {
            $amount = $amount;
            $fees_type_id = $type;
            $std_info = StudentRecord::where('id',$record_id)->where('student_id',$student_id)->select('class_id','section_id')->first();
            
            $discounts = SmFeesAssignDiscount::where('student_id', $student_id)->where('record_id',$record_id)->where('school_id', Auth::user()->school_id)->get();
            
            $banks = SmBankAccount::where('active_status', '=', 1)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();

                $applied_discount = [];
                foreach ($discounts as $fees_discount) {
                    $fees_payment = SmFeesPayment::where('record_id',$record_id)->where('active_status',1)->select('fees_discount_id')->where('fees_discount_id', $fees_discount->id)->where('school_id', Auth::user()->school_id)->first();
                    if (isset($fees_payment->fees_discount_id)) {
                        $applied_discount[] = $fees_payment->fees_discount_id;
                    }
                }


            $data['bank_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Bank')->where('school_id', Auth::user()->school_id)->first();
            $data['cheque_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Cheque')->where('school_id', Auth::user()->school_id)->first();


            $method['bank_info'] = SmPaymentMethhod::where('method', 'Bank')->where('school_id', Auth::user()->school_id)->first();
            $method['cheque_info'] = SmPaymentMethhod::where('method', 'Cheque')->where('school_id', Auth::user()->school_id)->first();

            return view('backEnd.studentPanel.fees_generate_modal_child', compact('amount','assign_id', 'discounts', 'fees_type_id', 'student_id', 'std_info','applied_discount', 'data', 'method','banks','record_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function childBankSlipStore(Request $request)
    {
        if($request->payment_mode == 'PayPal' || $request->payment_mode == 'Stripe' || $request->payment_mode == 'Paystack' || $request->payment_mode == 'CcAveune'){

            try{
                if(directFees()){
                    $request->validate([
                        'installment_id' => "required",
                        'amount'=> "required|regex:/^\d+(\.\d{1,2})?$/",
                        'payment_mode' => "required"
                    ]);

                    $date = strtotime($request->date);
                    $newformat = date('Y-m-d', $date);
    
                    $installment = DirectFeesInstallmentAssign::find($request->installment_id);
                    $installment->payment_date =  $newformat;
                    $installment->payment_mode = $request->payment_mode;
                    $installment->active_status = 0;
                    // $installment->save();
                    $payable_amount =  discountFees($installment->id);
                    $sub_payment = $installment->payments->sum('paid_amount');
                   
                    $last_inovoice = DireFeesInstallmentChildPayment::where('school_id',auth()->user()->school_id)->max('invoice_no');
                    $new_subPayment = new DireFeesInstallmentChildPayment();
                    $new_subPayment->direct_fees_installment_assign_id = $installment->id;
                    $new_subPayment->invoice_no = ( $last_inovoice +1 ) ?? 1;
                    $new_subPayment->amount = $request->amount;
                    $new_subPayment->paid_amount = $request->amount;
                    $new_subPayment->payment_date = $newformat;
                    $new_subPayment->payment_mode =  $request->payment_mode;
                    $new_subPayment->note = $request->note;
                    $new_subPayment->active_status = 0;
                    $new_subPayment->discount_amount = 0;
                    $new_subPayment->fees_type_id =  $installment->fees_type_id;
                    $new_subPayment->student_id = $request->student_id;
                    $new_subPayment->record_id = $request->record_id;
                    $new_subPayment->created_by = Auth::user()->id;
                    $new_subPayment->updated_by =  Auth::user()->id;
                    $new_subPayment->school_id = Auth::user()->school_id;
                    $new_subPayment->balance_amount = ( $payable_amount - ($sub_payment + $request->amount) ); 
                    $new_subPayment->save();
                    $data = [];
                    $serviceCharge = 0;
                    $gateway_setting = SmPaymentGatewaySetting::where('gateway_name',$request->payment_mode)->where('school_id',Auth::user()->school_id)->first();
                    if($gateway_setting){
                        $serviceCharge = chargeAmount($request->payment_mode, $request->amount);
                    }
                    
                    $data['amount'] = $request->amount + $serviceCharge;
                    $data['stripeToken'] = $request->stripeToken;
                    $data['sub_payment_id'] = $new_subPayment->id;
                    $data['installment_id'] = $installment->id;
                    $data['record_id'] = $installment->id;
                    $data['student_id'] = $installment->student_id;
                    $data['type'] = "direct_fees";
                    $data['method'] = $request->payment_mode;
                    $data['description'] = generalSetting()->school_name." Fees Installment";
                    if($request->payment_mode == 'CcAveune'){

                        $data['merchant_id'] = @$gateway_setting->cca_merchant_id;
                        $data['order_id'] = $data['type'].'_'.$new_subPayment->id;
                        $data['currency'] = generalSetting()->currency;
                        $data['redirect_url'] = route('payment.success','CcAveune');
                        $data['cancel_url']= route('payment.cancel','CcAveune');
                        $merchant_data='';
                        $working_key= @$gateway_setting->cca_working_key;//Shared by CCAVENUES
                        $access_code = @$gateway_setting->cca_access_code;//Shared by CCAVENUES

                        foreach ($data as $key => $value){
                            $merchant_data.=$key.'='.urlencode($value).'&';
                        }
                        $encrypted_data = $this->encrypt($merchant_data,$working_key);
                        return view('ccaveune::redirecformPage',compact('encrypted_data','access_code'));

                    }
                    $classMap = config('paymentGateway.'.$data['method']);
                    
                    $make_payment = new $classMap();
                    
                    return $make_payment->handle($data);
                    
                }

            }catch(\Exception $e){
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }

        }else{
            $request->validate([
                'slip' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
                'amount'=> "required|regex:/^\d+(\.\d{1,2})?$/"
            ]);
        }
        

        $student_record = StudentRecord::find($request->record_id);

        try {
            if($request->payment_mode=="bank"){
                if($request->bank_id==''){
                    Toastr::error('Bank Field Required', 'Failed');
                    return redirect()->back();
                }
            }

            $fileName = "";
            if ($request->file('slip') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('slip');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('slip');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/bankSlip/', $fileName);
                $fileName = 'public/uploads/bankSlip/' . $fileName;
            }

            $date = strtotime($request->date);

            $newformat = date('Y-m-d', $date);

            $payment_mode_name=ucwords($request->payment_mode);
            $payment_method=SmPaymentMethhod::where('method',$payment_mode_name)->first();

            $payment = new SmBankPaymentSlip();
            $payment->date = $newformat;
            $payment->amount = $request->amount;
            $payment->note = $request->note;
            $payment->slip = $fileName;
            $payment->student_id = $request->student_id;
            $payment->payment_mode = $request->payment_mode;
            if($payment_method->id==3){
                $payment->bank_id = $request->bank_id;
            }
            $payment->academic_id = getAcademicId();
            if(moduleStatusCheck('University')){
                $payment->un_academic_id= getAcademicId();
                $payment->un_fees_installment_id  = $request->installment_id;
                $payment->un_semester_label_id = $request->un_semester_label_id;

                $installment = UnFeesInstallmentAssign::find($request->installment_id);
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
                $new_subPayment->amount = $request->amount;
                $new_subPayment->paid_amount = $request->amount;
                $new_subPayment->payment_date = $newformat;
                $new_subPayment->payment_mode =  $request->payment_mode;
                $new_subPayment->note = $request->note;
                $new_subPayment->slip = $fileName;
                $new_subPayment->active_status = 0;
                $new_subPayment->bank_id = $request->bank_id;
                $new_subPayment->discount_amount = 0;
                $new_subPayment->fees_type_id =  $installment->fees_type_id;
                $new_subPayment->student_id = $request->student_id;
                $new_subPayment->record_id = $request->record_id;
                $new_subPayment->un_semester_label_id = $request->un_semester_label_id;;
                $new_subPayment->un_academic_id = getAcademicId();
                $new_subPayment->created_by = Auth::user()->id;
                $new_subPayment->updated_by =  Auth::user()->id;
                $new_subPayment->school_id = Auth::user()->school_id;
                $new_subPayment->balance_amount = ( $payable_amount - ($sub_payment + $request->amount) ); 
                $new_subPayment->save();

                $payment->child_payment_id = $new_subPayment->id;

            }elseif(directFees()){
                $payment->class_id = $student_record->class_id;
                $payment->section_id = $student_record->section_id;
                $payment->record_id = $student_record->id;
                $payment->school_id = Auth::user()->school_id;
                $installment = DirectFeesInstallmentAssign::find($request->installment_id);
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
                $new_subPayment->amount = $request->amount;
                $new_subPayment->paid_amount = $request->amount;
                $new_subPayment->payment_date = $newformat;
                $new_subPayment->payment_mode =  $request->payment_mode;
                $new_subPayment->note = $request->note;
                $new_subPayment->slip = $fileName;
                $new_subPayment->active_status = 0;
                $new_subPayment->bank_id = $request->bank_id;
                $new_subPayment->discount_amount = 0;
                $new_subPayment->fees_type_id =  $installment->fees_type_id;
                $new_subPayment->student_id = $request->student_id;
                $new_subPayment->record_id = $request->record_id;
                $new_subPayment->created_by = Auth::user()->id;
                $new_subPayment->updated_by =  Auth::user()->id;
                $new_subPayment->school_id = Auth::user()->school_id;
                $new_subPayment->balance_amount = ( $payable_amount - ($sub_payment + $request->amount) ); 
                $new_subPayment->save();
                
                $payment->child_payment_id = $new_subPayment->id;
                $payment->installment_id = $request->installment_id;
            }
            
            else{
                $payment->assign_id= $request->assign_id;
                $payment->class_id = $request->class_id;
                $payment->section_id = $request->section_id;
                $payment->record_id = $request->record_id;
                $payment->school_id = Auth::user()->school_id;
                $payment->fees_type_id = $request->fees_type_id;
            }

            $payment->save();

            Toastr::success('Payment Added, Please Wait for approval', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesGenerateModalChildView($id,$type_id)
    {

        $fees_payments = SmBankPaymentSlip::where('student_id',$id)->where('fees_type_id',$id)->get();
        return view('backEnd.studentPanel.view_bank_payment', compact('fees_payments'));
    }

    public function feesGenerateModalBankView($sid,$ft_id)
    {
        $fees_payments = SmBankPaymentSlip::where('student_id',$sid)->where('fees_type_id',$ft_id)->get();
        $amount = SmBankPaymentSlip::where('student_id',$sid)->where('fees_type_id',$ft_id)->sum('amount');
        return view('backEnd.studentPanel.view_bank_payment', compact('fees_payments','amount'));
    }

    public function childBankSlipDelete(Request $request)
    { 
        try {
            if(moduleStatusCheck('University')){
                $installment_id = $request->id;
                $slip = SmBankPaymentSlip::where('un_fees_installment_id',$installment_id)->first();
                if($slip){
                    $slip->delete();
                    $assign = UnFeesInstallmentAssign::find($installment_id);
                    $assign->note = null;
                    $assign->slip = null;
                    $assign->active_status = 0;
                    $assign->bank_id = null;
                    $assign->save();
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }
            }
            else{
                $visitor = SmBankPaymentSlip::find($request->id);
                if ($visitor->slip != "") {
                    $path = url('/') . $visitor->slip;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
                $result = $visitor->delete();
    
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            }

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function directFeesGenerateModalChild(Request $request, $amount , $installment_id, $record_id)
    {
        try {
            $amount = $amount;
            $std_info = StudentRecord::find($record_id);
            $student_id = $std_info->student_id;
            $discounts = SmFeesAssignDiscount::where('student_id', $std_info->student_id)->where('record_id',$record_id)->where('school_id', Auth::user()->school_id)->get();
            
            $banks = SmBankAccount::where('active_status', '=', 1)
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
                    
                $applied_discount = [];
                foreach ($discounts as $fees_discount) {
                    $fees_payment = SmFeesPayment::where('record_id',$record_id)->where('active_status',1)->select('fees_discount_id')->where('fees_discount_id', $fees_discount->id)->where('school_id', Auth::user()->school_id)->first();
                    if (isset($fees_payment->fees_discount_id)) {
                        $applied_discount[] = $fees_payment->fees_discount_id;
                    }
                }

            $data['bank_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Bank')->where('school_id', Auth::user()->school_id)->first();
            $data['cheque_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Cheque')->where('school_id', Auth::user()->school_id)->first();
            $method['bank_info'] = SmPaymentMethhod::where('method', 'Bank')->where('school_id', Auth::user()->school_id)->first();
            $data['cheque_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Cheque')->where('school_id', Auth::user()->school_id)->first();
            $method['cheque_info'] = SmPaymentMethhod::where('method', 'Cheque')->where('school_id', Auth::user()->school_id)->first();
            $data['PayPal'] = SmPaymentMethhod::where('method', 'PayPal')
                            ->where('school_id', Auth::user()->school_id)
                            ->first('active_status');
            $data['Stripe'] = SmPaymentMethhod::where('method', 'Stripe')
                                ->where('school_id', Auth::user()->school_id)
                                ->first('active_status');
            $data['Paystack'] = SmPaymentMethhod::where('method', 'Paystack')
                                ->where('school_id', Auth::user()->school_id)
                                ->first('active_status');
            $data['CcAveune'] = SmPaymentMethhod::where('method', 'CcAveune')
                                ->where('school_id', Auth::user()->school_id)
                                ->first('active_status');
            $method['PayPal'] = SmPaymentGatewaySetting::where('gateway_name', 'PayPal')->where('school_id', Auth::user()->school_id)->first();
            $method['Stripe'] = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')->where('school_id', Auth::user()->school_id)->first();
            $method['Paystack'] = SmPaymentGatewaySetting::where('gateway_name', 'Paystack')->where('school_id', Auth::user()->school_id)->first();
            $installment = DirectFeesInstallmentAssign::find($installment_id);
            $balance_fees = discountFees($installment_id)  - $installment->payments->sum('paid_amount');
            return view('backEnd.feesCollection.directFees.fees_generate_modal_child', compact('amount','discounts','student_id', 'std_info','applied_discount', 'data', 'method','banks','record_id','installment_id','balance_fees'));
        } catch (\Exception $e) {
       
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function directFeesPaymentStripe($installment_id){
        $installment = DirectFeesInstallmentAssign::find($installment_id);
        $stripe_info = SmPaymentGatewaySetting::where('gateway_name', 'stripe')->where('school_id', Auth::user()->school_id)->first();
        return view('backEnd.feesCollection.directFees.stripePaymentModal', compact('stripe_info', 'installment'));

    }

    public function directFeesTotalPayment($record_id){

        try{
            $studentRerod = StudentRecord::find($record_id);
            $student_id =   $studentRerod->student_id; 
    
            $banks = SmBankAccount::where('school_id', Auth::user()->school_id)
                    ->get();
            $discounts = [];
            $data['bank_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Bank')
                                ->where('school_id', Auth::user()->school_id)
                                ->first();
    
            $data['cheque_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Cheque')
                                ->where('school_id', Auth::user()->school_id)
                                ->first();
    
            $method['bank_info'] = SmPaymentMethhod::where('method', 'Bank')
                                ->where('school_id', Auth::user()->school_id)
                                ->first();
    
            $method['cheque_info'] = SmPaymentMethhod::where('method', 'Cheque')
                                    ->where('school_id', Auth::user()->school_id)
                                    ->first();
            $data['PayPal'] = SmPaymentMethhod::where('method', 'PayPal')
                            ->where('school_id', Auth::user()->school_id)
                            ->first('active_status');
            $data['Stripe'] = SmPaymentMethhod::where('method', 'Stripe')
                            ->where('school_id', Auth::user()->school_id)
                            ->first('active_status');
            $data['Paystack'] = SmPaymentMethhod::where('method', 'Paystack')
                                ->where('school_id', Auth::user()->school_id)
                                ->first('active_status');
            if(moduleStatusCheck('CcAveune')) {
                $data['CcAveune'] = SmPaymentMethhod::where('method', 'CcAveune')
                ->where('school_id', Auth::user()->school_id)
                ->first('active_status');
            }   
           

            $method['PayPal'] = SmPaymentGatewaySetting::where('gateway_name', 'PayPal')->where('school_id', Auth::user()->school_id)->first();
            $method['Stripe'] = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')->where('school_id', Auth::user()->school_id)->first();
            $method['Paystack'] = SmPaymentGatewaySetting::where('gateway_name', 'Paystack')->where('school_id', Auth::user()->school_id)->first();                        
            $total_amount = DirectFeesInstallmentAssign::where('record_id', $record_id)->sum('amount');
            $total_discount = DirectFeesInstallmentAssign::where('record_id', $record_id)->sum('discount_amount');
            $total_paid = DirectFeesInstallmentAssign::where('record_id', $record_id)->sum('paid_amount');
            $balace_amount = $total_amount -  ($total_discount +  $total_paid);
            $amount = $balace_amount;
            return view('backEnd.feesCollection.directFees.total_payment_modal', compact('amount','discounts',  'student_id', 'data', 'method','banks','record_id','balace_amount'));

        }
        catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function directFeesTotalPaymentSubmit(Request $request){
       
        try{
            if($request->payment_mode == "Stripe" || $request->payment_mode == "Paystack" || $request->payment_mode == "PayPal" || $request->payment_mode == "CcAveune"){
                $data = [];
                    $serviceCharge = 0;
                    $gateway_setting = SmPaymentGatewaySetting::where('gateway_name',$request->payment_mode)->where('school_id',Auth::user()->school_id)->first();
                    if($gateway_setting){
                        $serviceCharge = chargeAmount($request->payment_mode, $request->request_amount);
                    }
                    
                    $data['request_amount'] = $request->request_amount ;
                    $data['amount'] = $request->request_amount + $serviceCharge;
                    $data['stripeToken'] = $request->stripeToken;
                    $data['record_id'] = $request->record_id;
                    $data['student_id'] = $request->student_id;
                    $data['type'] = "direct_fees_total";
                    $data['method'] = $request->payment_mode;
                    $data['description'] = generalSetting()->school_name." Fees Installment";

                    if($request->payment_mode == "CcAveune"){
                        $data['merchant_id'] = @$gateway_setting->cca_merchant_id;
                        $data['order_id'] = $data['type'].'_'.$data['record_id'].'_'.$request->request_amount;
                        $data['currency'] = generalSetting()->currency;
                        $data['redirect_url'] = route('payment.success','CcAveune');
                        $data['cancel_url']= route('payment.cancel','CcAveune');
                        $merchant_data='';
                        $working_key= @$gateway_setting->cca_working_key;//Shared by CCAVENUES
                        $access_code = @$gateway_setting->cca_access_code;//Shared by CCAVENUES

                        foreach ($data as $key => $value){
                            $merchant_data.=$key.'='.urlencode($value).'&';
                        }
                        $encrypted_data = $this->encrypt($merchant_data,$working_key);
                        return view('ccaveune::redirecformPage',compact('encrypted_data','access_code'));
                    }
                    $classMap = config('paymentGateway.'.$data['method']);
                    $make_payment = new $classMap();
                    return $make_payment->handle($data);
            }
           
                $record_id = $request->record_id;
                $student_record = StudentRecord::find($record_id);
                $student_id = $request->student_id;
                $request_amount = $request->request_amount;
                $after_paid = $request_amount;
                
                $installments = DirectFeesInstallmentAssign::where('record_id', $record_id)->get();
                $total_paid = $installments->sum('paid_amount');
                $total_amount = $installments->sum('amount');
                $total_discount = $installments->sum('discount_amount');
                $balace_amount = $total_amount - ($total_discount +  $total_paid);
                if($balace_amount <  $request_amount){
                    Toastr::error('Amount is greater than due', 'Failed');
                    return redirect()->back();
                }

                if($request->payment_mode=="bank"){
                    if($request->bank_id==''){
                        Toastr::error('Bank Field Required', 'Failed');
                        return redirect()->back();
                    }
                }

                $fileName = "";
                if ($request->file('slip') != "") {
                    $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                    $file = $request->file('slip');
                    $fileSize =  filesize($file);
                    $fileSizeKb = ($fileSize / 1000000);
                    if($fileSizeKb >= $maxFileSize){
                        Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                        return redirect()->back();
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
                        $payment->student_id = $request->student_id;
                        $payment->payment_mode = $request->payment_mode;
                        if($payment_method->id==3){
                            $payment->bank_id = $request->bank_id;
                        }
                        $payment->academic_id = getAcademicId();
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
                            $payment->school_id = Auth::user()->school_id;
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
                            $new_subPayment->student_id = $request->student_id;
                            $new_subPayment->record_id = $request->record_id;
                            $new_subPayment->created_by = Auth::user()->id;
                            $new_subPayment->updated_by =  Auth::user()->id;
                            $new_subPayment->school_id = Auth::user()->school_id;
                            $new_subPayment->balance_amount = ( $payable_amount - ($sub_payment + $paid_amount) ); 
                            $new_subPayment->save();
                            $payment->child_payment_id = $new_subPayment->id;
                            $payment->installment_id = $installment->id;
                        }
                        $payment->save();
                        $after_paid -= ( $paid_amount);
                    }
                }
                Toastr::success('Payment Added, Please Wait for approval', 'Success');
                return redirect()->back();
        }catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}