<?php

namespace Modules\Fees\Http\Controllers\api;

use App\User;
use App\SmClass;
use App\SmSchool;
use App\SmStudent;
use App\SmAddIncome;
use App\SmBankAccount;
use App\SmPaymentMethhod;
use App\Models\StudentRecord;
use App\SmPaymentGatewaySetting;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Fees\Entities\FmFeesType;
use Modules\Fees\Entities\FmFeesGroup;
use Modules\Fees\Entities\FmFeesInvoice;
use Modules\Fees\Entities\FmFeesTransaction;
use Illuminate\Validation\ValidationException;
use Modules\Fees\Entities\FmFeesInvoiceChield;
use Modules\Wallet\Entities\WalletTransaction;
use Modules\Fees\Entities\FmFeesTransactionChield;
use Modules\Fees\Http\Controllers\FeesExtendedController;
use Modules\Fees\Http\Requests\StudentAddFeesPaymentRequest;

class StudentFeesController extends Controller
{
    public function studentFeesList($id)
    {
        $student_id = $id;
        $records = StudentRecord::where('is_promote',0)
                ->where('student_id', $student_id)
                ->where('academic_id',getAcademicId())
                ->with('feesInvoice')
                ->get()->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'class' => $value->class_id,
                        'section' => $value->section_id,
                        'roll_no' => $value->role_no,
                        'feesInvoice' => $value->feesInvoice,
                    ];
                });
        return response()->json(compact('student_id','records'));
    }

    public function studentRecordFeesList($id, $record_id)
    {
        try{
            $student_id = $id;
            $records = FmFeesInvoice::where('student_id', $student_id)->where('record_id',$record_id)->get()
            ->map(function ($value) {
                $amount = $value->Tamount;
                $weaver = $value->Tweaver;
                $fine = $value->Tfine;
                $paid_amount = $value->Tpaidamount;
                $sub_total = $value->Tsubtotal;
                $balance = ($amount + $fine) - ($paid_amount + $weaver);
                return [
                    'id' => $value->id,
                    'amount' => (float)$amount,
                    'weaver' => $weaver,
                    'fine' => $fine,
                    'paid_amount' => $paid_amount,
                    'sub_total' => (float)$sub_total,
                    'balance' => $balance,
                    'student' => $value->studentInfo->full_name ? $value->studentInfo->full_name : '',
                    'class' => $value->recordDetail->class->class_name ? $value->recordDetail->class->class_name : '',
                    'section' => $value->recordDetail->section->section_name ? $value->recordDetail->section->section_name : '',
                    'status' => $balance == 0 ? 'paid' : ($value->Tpaidamount > 0 ? 'partial': 'unpaid'),
                    'date' => dateConvert($value->create_date),
                ];
            });
            return response()->json(compact('student_id','records'));
        }catch(\Exception $e){
            return response()->json(['message'=>'Error']);
        }
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
                                ->where('school_id',Auth::user()->school_id)
                                ->get()->map(function ($value){
                                    return [
                                        'payment_method'=>$value->method,
                                    ];
                                });
            
            $bankAccounts = SmBankAccount::where('school_id',Auth::user()->school_id)
                            ->where('active_status',1)
                            ->where('academic_id', getAcademicId())
                            ->get()->map(function ($value){
                                return [
                                    'id'=>$value->id,
                                    'bank_name'=>$value->bank_name,
                                    'account_number'=>$value->account_number,
                                ];
                            });
            
            $invoiceInfo = FmFeesInvoice::with('studentInfo')->find($id);
            $walletBalance = $invoiceInfo->studentInfo->user->wallet_balance;

            $invoiceDetails = FmFeesInvoiceChield::where('fees_invoice_id',$invoiceInfo->id)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('academic_id', getAcademicId())
                            ->get()->map(function ($value){
                                return [
                                    'fees_type'=>$value->fees_type,                                   
                                    'fees_type_name'=>$value->feesType ? $value->feesType->name : '',
                                    'amount'=>$value->amount,
                                    'due_amount'=>$value->due_amount,
                                    'weaver'=>$value->weaver,
                                    'fine'=>$value->fine,
                                    'note'=>$value->note,
                                ];
                            });;

            $stripe_info = SmPaymentGatewaySetting::where('gateway_name', 'stripe')
                            ->where('school_id', Auth::user()->school_id)
                            ->first();

            return response()->json(compact('classes','feesGroups','feesTypes','paymentMethods','bankAccounts','invoiceInfo','invoiceDetails','stripe_info','walletBalance'));
        }catch(\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function studentFeesPaymentStore(StudentAddFeesPaymentRequest $request)
    {
            if($request->total_paid_amount <= 0){
                throw ValidationException::withMessages(['paid_amount_error'=>'Paid Amount Can Not Be Blank']);
            }
            $destination = 'public/uploads/student/document/';
            $file = fileUpload($request->file('file'), $destination);

            $record = StudentRecord::find($request->student_id);
            $student=SmStudent::with('parents')->find($record->student_id);

            if($request->payment_method == "Wallet"){
                $user = User::find(Auth::user()->id);
                if($user->wallet_balance == 0){
                    throw ValidationException::withMessages(['wallet_balance'=> 'Insufficiant Balance']);
                }elseif($user->wallet_balance >= $request->total_paid_amount){
                    $user->wallet_balance = $user->wallet_balance - $request->total_paid_amount;
                    $user->update();
                }else{
                    throw ValidationException::withMessages(['wallet_balance'=> 'Total Amount Is Grater Than Wallet Amount']);
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
                $storeTransaction->academic_id = getAcademicId();
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
                        $storeTransactionChield->academic_id = getAcademicId();
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
            }elseif($request->payment_method == "Cheque" || $request->payment_method == "Bank"){
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
                $storeTransaction->paid_status = 'pending';
                $storeTransaction->school_id = Auth::user()->school_id;
                $storeTransaction->academic_id = getAcademicId();
                $storeTransaction->save();
                
                foreach($request->fees_type as $key=>$type){
                    if($request->paid_amount[$key] > 0){
                        $storeTransactionChield = new FmFeesTransactionChield();
                        $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                        $storeTransactionChield->fees_type = $type;
                        $storeTransactionChield->paid_amount = $request->paid_amount[$key] - $request->extraAmount[$key];
                        $storeTransactionChield->note = $request->note[$key];
                        $storeTransactionChield->school_id = Auth::user()->school_id;
                        $storeTransactionChield->academic_id = getAcademicId();
                        $storeTransactionChield->save();
                    }
                }
            }else{
                $storeTransaction = new FmFeesTransaction();
                $storeTransaction->fees_invoice_id = $request->invoice_id;
                $storeTransaction->payment_note = $request->payment_note;
                $storeTransaction->payment_method = $request->payment_method;
                $storeTransaction->student_id = $record->student_id;
                $storeTransaction->record_id = $record->id;
                $storeTransaction->add_wallet_money = $request->add_wallet;
                $storeTransaction->user_id = Auth::user()->id;
                $storeTransaction->paid_status = 'pending';
                $storeTransaction->school_id = Auth::user()->school_id;
                $storeTransaction->academic_id = getAcademicId();
                $storeTransaction->save();

                foreach($request->fees_type as $key=>$type){
                    if($request->paid_amount[$key] > 0){
                        $storeTransactionChield = new FmFeesTransactionChield();
                        $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                        $storeTransactionChield->fees_type = $type;
                        $storeTransactionChield->paid_amount = $request->paid_amount[$key]- $request->extraAmount[$key];
                        $storeTransactionChield->note = $request->note[$key];
                        $storeTransactionChield->school_id = Auth::user()->school_id;
                        $storeTransactionChield->academic_id = getAcademicId();
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

                return response()->json($data);
            }

            //Notification
            sendNotification("Add Fees Payment", null, $student->user_id, 2);
            sendNotification("Add Fees Payment", null, $student->parents->user_id, 3);
            sendNotification("Add Fees Payment", null, 1, 1);
            
            return response()->json(['message'=>'Payment Sucessfully']);
    }

    public function onlinePaymentSucess($type, $transcationId)
    {
        try{
            if($type == 'Fees'){
                $extendedController = new FeesExtendedController();
                $extendedController->addFeesAmount($transcationId, null);
            }
            return response()->json(['message'=>'Payment Sucessfully']);
        }catch(\Exception $e){
            return response()->json(['message'=>'Error']);
        }
        
    }

    public function walletBalance($user_id)
    {
        try{
            $userInfo = User::find($user_id);
            $walletBalance = $userInfo->wallet_balance;
            return response()->json(compact('walletBalance'));
        }catch(\Exception $e){
            return response()->json(['message'=>'Error']);
        }
        
    }
}
