<?php

namespace Modules\Fees\Http\Controllers;

use App\User;
use App\SmClass;
use App\SmSchool;
use App\SmStudent;
use App\SmAddIncome;
use App\SmBankAccount;
use App\SmPaymentMethhod;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmPaymentGatewaySetting;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Fees\Entities\FmFeesType;
use Modules\Fees\Entities\FmFeesGroup;
use Modules\Fees\Entities\FmFeesInvoice;
use Illuminate\Support\Facades\Validator;
use Modules\Fees\Entities\FmFeesTransaction;
use Modules\Fees\Entities\FmFeesInvoiceChield;
use Modules\Wallet\Entities\WalletTransaction;
use Modules\Fees\Entities\FmFeesTransactionChield;
use Modules\CcAveune\Http\Controllers\CcAveuneController;
use Modules\Fees\Http\Controllers\FeesExtendedController;

class StudentFeesController extends Controller
{
    public function studentFeesList()
    {
        $user = auth()->user();
        if($user->role_id != 2) {
            abort(404);
        }
        $student_id = $user->student->id;
        if(moduleStatusCheck('University')){
            $records = StudentRecord::where('is_promote',0)
            ->where('student_id', $student_id)
            ->where('un_academic_id',getAcademicId())
            ->with('feesInvoice')
            ->get();
        }else{
            $records = StudentRecord::where('is_promote',0)
            ->where('student_id', $student_id)
            ->where('academic_id',getAcademicId())
            ->with('feesInvoice')
            ->get();
        }

        return view('fees::student.feesInfo',compact('student_id','records'));
    }
    public function studentFeesListParent($id)
    {
        $student_id = $id;
        if(moduleStatusCheck('University')){
            $records = StudentRecord::where('is_promote',0)
            ->where('student_id', $student_id)
            ->where('un_academic_id',getAcademicId())
            ->with('feesInvoice')
            ->get();
        }else{
            $records = StudentRecord::where('is_promote',0)
            ->where('student_id', $student_id)
            ->where('academic_id',getAcademicId())
            ->with('feesInvoice')
            ->get();
        }

        return view('fees::student.feesInfo',compact('student_id','records'));
    }

    public function studentAddFeesPayment($id)
    {
        try{
            $classes = SmClass::where('school_id',Auth::user()->school_id)
            ->where('academic_id',getAcademicId())
            ->get();

            $feesGroups = FmFeesGroup::where('school_id',Auth::user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();

            $feesTypes = FmFeesType::where('school_id',Auth::user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();

            $paymentMethods = SmPaymentMethhod::whereNotIn('method', ["Cash"])
                                ->where('school_id',Auth::user()->school_id);

            if(!moduleStatusCheck('RazorPay')){
                $paymentMethods = $paymentMethods->where('method', '!=', 'RazorPay');
            }


            $paymentMethods = $paymentMethods->get();

            
            $bankAccounts = SmBankAccount::where('school_id',Auth::user()->school_id)
                            ->where('active_status',1)
                            ->where('academic_id', getAcademicId())
                            ->get();
            
            $invoiceInfo = FmFeesInvoice::find($id);
            $invoiceDetails = FmFeesInvoiceChield::where('fees_invoice_id',$invoiceInfo->id)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('academic_id', getAcademicId())
                            ->get();

            $stripe_info = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')
                            ->where('school_id', Auth::user()->school_id)
                            ->first();
            $razorpay_info = null;
            if(moduleStatusCheck('RazorPay')){
                $razorpay_info = SmPaymentGatewaySetting::where('gateway_name', 'RazorPay')
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
            }

            return view('fees::student.studentAddPayment',compact('classes','feesGroups','feesTypes','paymentMethods','bankAccounts','invoiceInfo','invoiceDetails','stripe_info', 'razorpay_info'));
        }catch(\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function studentFeesPaymentStore(Request $request)
    {
        if($request->total_paid_amount == null){
            Toastr::warning('Paid Amount Can Not Be Blank', 'Failed');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'payment_method' =>  'required',
            'bank' =>  'required_if:payment_method,Bank',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try{
            
            $destination = 'public/uploads/student/document/';
            $file = fileUpload($request->file('file'), $destination);

            $record = StudentRecord::find($request->student_id);
            $student=SmStudent::with('parents')->find($record->student_id);
            
            if($request->payment_method == "Wallet"){
                $user = User::find(Auth::user()->id);
                if($user->wallet_balance == 0){
                    Toastr::warning('Insufficiant Balance', 'Warning');
                    return redirect()->back();
                }elseif($user->wallet_balance >= $request->total_paid_amount){
                    $user->wallet_balance = $user->wallet_balance - $request->total_paid_amount;
                    $user->update();
                }else{
                    Toastr::warning('Total Amount Is Grater Than Wallet Amount', 'Warning');
                    return redirect()->back();
                }
                $addPayment = new WalletTransaction();
                if($request->add_wallet > 0){
                    $addAmount = $request->total_paid_amount - $request->add_wallet;
                    $addPayment->amount= $addAmount;
                }else{
                    $addPayment->amount= $request->total_paid_amount;
                }
                $addPayment->payment_method= $request->payment_method;
                $addPayment->user_id= $user->id;
                $addPayment->type= 'expense';
                $addPayment->status= 'approve';
                $addPayment->note= 'Fees Payment';
                $addPayment->school_id= Auth::user()->school_id;
                $addPayment->academic_id= getAcademicId();
                $addPayment->save();

                $storeTransaction = new FmFeesTransaction();
                $storeTransaction->fees_invoice_id = $request->invoice_id;
                $storeTransaction->payment_note = $request->payment_note;
                $storeTransaction->payment_method = $request->payment_method;
                $storeTransaction->add_wallet_money = $request->add_wallet;
                $storeTransaction->bank_id = $request->bank;
                $storeTransaction->student_id = $record->student_id;
                $storeTransaction->record_id = $record->id;
                $storeTransaction->user_id = Auth::user()->id;
                $storeTransaction->file = $file;
                $storeTransaction->paid_status = 'approve';
                $storeTransaction->school_id = Auth::user()->school_id;
                if(moduleStatusCheck('University')){
                    $storeTransaction->un_academic_id = getAcademicId();
                }else{
                    $storeTransaction->academic_id = getAcademicId();
                }
                $storeTransaction->save();

                foreach($request->fees_type as $key=>$type){
                    $id = FmFeesInvoiceChield::where('fees_invoice_id',$request->invoice_id)->where('fees_type',$type)->first('id')->id;
                
                    $storeFeesInvoiceChield = FmFeesInvoiceChield::find($id);
                    $storeFeesInvoiceChield->due_amount = $request->due[$key];
                    $storeFeesInvoiceChield->paid_amount = $storeFeesInvoiceChield->paid_amount + $request->paid_amount[$key] - $request->extraAmount[$key];
                    $storeFeesInvoiceChield ->update();
                    
                    if($request->paid_amount[$key] > 0){
                        $storeTransactionChield = new FmFeesTransactionChield();
                        $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                        $storeTransactionChield->fees_type = $type;
                        $storeTransactionChield->paid_amount = $request->paid_amount[$key] - $request->extraAmount[$key];
                        $storeTransactionChield->note = $request->note[$key];
                        $storeTransactionChield->school_id = Auth::user()->school_id;
                        if(moduleStatusCheck('University')){
                            $storeTransactionChield->un_academic_id = getAcademicId();
                        }else{
                            $storeTransactionChield->academic_id = getAcademicId();
                        }
                        $storeTransactionChield->save();
                    }
                }

                if ($request->add_wallet > 0) {
                    $user->wallet_balance = $user->wallet_balance + $request->add_wallet;
                    $user->update();
        
                    $addPayment = new WalletTransaction();
                    $addPayment->amount = $request->add_wallet;
                    $addPayment->payment_method = $request->payment_method;
                    $addPayment->user_id = $user->id;
                    $addPayment->type = 'diposit';
                    $addPayment->status = 'approve';
                    $addPayment->note = 'Fees Extra Payment Add';
                    $addPayment->school_id = Auth::user()->school_id;
                    $addPayment->academic_id = getAcademicId();
                    $addPayment->save();
        
                    $school = SmSchool::find($user->school_id);
                    $compact['full_name'] = $user->full_name;
                    $compact['method'] = $request->payment_method;
                    $compact['create_date'] = date('Y-m-d');
                    $compact['school_name'] = $school->school_name;
                    $compact['current_balance'] = $user->wallet_balance;
                    $compact['add_balance'] = $request->add_wallet;
                    $compact['previous_balance'] = $user->wallet_balance - $request->add_wallet;
        
                    @send_mail($user->email, $user->full_name, "fees_extra_amount_add", $compact);
                    sendNotification($user->id, null, null, $user->role_id, "Fees Xtra Amount Add");
                }

                // Income
                $payment_method = SmPaymentMethhod::where('method', $request->payment_method)->first();
                $income_head = generalSetting();

                $add_income = new SmAddIncome();
                $add_income->name = 'Fees Collect';
                $add_income->date = date('Y-m-d');
                $add_income->amount = $request->total_paid_amount;
                $add_income->fees_collection_id = $storeTransaction->id;
                $add_income->active_status = 1;
                $add_income->income_head_id = $income_head->income_head_id;
                $add_income->payment_method_id = $payment_method->id;
                $add_income->created_by = Auth()->user()->id;
                $add_income->school_id = Auth::user()->school_id;
                $add_income->academic_id = getAcademicId();
                $add_income->save();
            }elseif($request->payment_method == "Cheque" || $request->payment_method == "Bank" || $request->payment_method == "MercadoPago") {
                $storeTransaction = new FmFeesTransaction();
                $storeTransaction->fees_invoice_id = $request->invoice_id;
                $storeTransaction->payment_note = $request->payment_note;
                $storeTransaction->payment_method = $request->payment_method;
                $storeTransaction->add_wallet_money = $request->add_wallet;
                $storeTransaction->bank_id = $request->bank;
                $storeTransaction->student_id = $record->student_id;
                $storeTransaction->record_id = $record->id;
                $storeTransaction->user_id = auth()->user()->id;
                $storeTransaction->file = $file;
                $storeTransaction->paid_status = 'pending';
                $storeTransaction->school_id = auth()->user()->school_id;
                if(moduleStatusCheck('University')){
                    $storeTransaction->un_academic_id = getAcademicId();
                }else{
                    $storeTransaction->academic_id = getAcademicId();
                }
                $storeTransaction->save();
                
                foreach($request->fees_type as $key=>$type){
                    if($request->paid_amount[$key] > 0){
                        $storeTransactionChield = new FmFeesTransactionChield();
                        $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                        $storeTransactionChield->fees_type = $type;
                        $storeTransactionChield->paid_amount = $request->paid_amount[$key] - $request->extraAmount[$key];
                        $storeTransactionChield->service_charge = chargeAmount($request->payment_method, $request->paid_amount[$key]);
                        $storeTransactionChield->note = $request->note[$key];
                        $storeTransactionChield->school_id = auth()->user()->school_id;
                        if(moduleStatusCheck('University')){
                            $storeTransactionChield->un_academic_id = getAcademicId();
                        }else{
                            $storeTransactionChield->academic_id = getAcademicId();
                        }
                        $storeTransactionChield->save();
                    }
                }
                if(moduleStatusCheck('MercadoPago')){
                    if(@$request->payment_method == "MercadoPago"){
                        $storeTransaction->total_paid_amount = $request->total_paid_amount;
                        $storeTransaction->save();
                        return redirect()->route('mercadopago.mercadopago-fees-payment',['traxId' =>$storeTransaction->id]);
                    }
                }
            } else{
                $storeTransaction = new FmFeesTransaction();
                $storeTransaction->fees_invoice_id = $request->invoice_id;
                $storeTransaction->payment_note = $request->payment_note;
                $storeTransaction->payment_method = $request->payment_method;
                $storeTransaction->student_id = $record->student_id;
                $storeTransaction->record_id = $record->id;
                $storeTransaction->add_wallet_money = $request->add_wallet;
                $storeTransaction->user_id = auth()->user()->id;
                $storeTransaction->paid_status = 'pending';
                $storeTransaction->school_id = auth()->user()->school_id;
                if(moduleStatusCheck('University')){
                    $storeTransaction->un_academic_id = getAcademicId();
                }else{
                    $storeTransaction->academic_id = getAcademicId();
                }
                $storeTransaction->save();
                

                foreach($request->fees_type as $key=>$type){
                    if($request->paid_amount[$key] > 0){
                        $storeTransactionChield = new FmFeesTransactionChield();
                        $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                        $storeTransactionChield->fees_type = $type;
                        $storeTransactionChield->paid_amount = $request->paid_amount[$key]- $request->extraAmount[$key];
                        $storeTransactionChield->service_charge = chargeAmount($request->payment_method, $request->paid_amount[$key]);
                        $storeTransactionChield->note = $request->note[$key];
                        $storeTransactionChield->school_id = Auth::user()->school_id;
                        if(moduleStatusCheck('University')){
                            $storeTransactionChield->un_academic_id = getAcademicId();
                        }else{
                            $storeTransactionChield->academic_id = getAcademicId();
                        }
                        $storeTransactionChield->save();
                    }
                }

                $data = [];
                $data['invoice_id'] = $request->invoice_id;
                $data['amount'] = $request->total_paid_amount;
                $data['payment_method'] = $request->payment_method;
                $data['description'] = "Fees Payment";
                $data['type'] = "Fees";
                $data['student_id'] = $request->student_id;
                $data['stripeToken'] = $request->stripeToken;
                $data['transcationId'] = $storeTransaction->id;
                $data['transcationId'] = 1;
                if($data['payment_method'] == 'RazorPay'){
                    $extendedController = new FeesExtendedController();
                    $extendedController->addFeesAmount($storeTransaction->id, null);
                }elseif($data['payment_method'] == 'CcAveune'){
                    $ccAvenewPaymentController = new CcAveuneController();
                    $ccAvenewPaymentController->studentFeesPay($data['amount'] , $data['transcationId'], $data['type']);
                }else{
                    $classMap = config('paymentGateway.'.$data['payment_method']);
                    $make_payment = new $classMap();
                    $url = $make_payment->handle($data);
                    if(!$url){
                        $url = 'fees/student-fees-list/'.$record->student_id;
                    }
                    if($request->wantsJson()){
                        return response()->json(['goto'=>$url]);
                    }else{
                        return redirect($url);
                    }
                }
            }

            //Notification
            sendNotification("Add Fees Payment", null, $student->user_id, 2);
            sendNotification("Add Fees Payment", null, $student->parents->user_id, 3);
            sendNotification("Add Fees Payment", null, 1, 1);
            Toastr::success('Save Successful', 'Success');
            return redirect()->to(url('fees/student-fees-list', $record->student_id));
        }catch(\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
