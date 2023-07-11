<?php

namespace App\Http\Controllers\Admin\FeesCollection;

use App\User;
use App\SmClass;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\SmAddIncome;
use App\SmsTemplate;
use App\SmFeesAssign;
use App\SmFeesMaster;
use App\SmBankAccount;
use App\SmFeesPayment;
use App\SmNotification;
use App\SmBankStatement;
use App\SmPaymentMethhod;
use App\SmBankPaymentSlip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\DirectFeesInstallmentAssign;
use Illuminate\Support\Facades\Notification;
use App\Models\DireFeesInstallmentChildPayment;
use App\Notifications\FeesApprovedNotification;
use Modules\University\Entities\UnFeesInstallmentAssign;
use Modules\University\Entities\UnFeesInstallAssignChildPayment;
use App\Http\Requests\Admin\FeesCollection\SmFeesBankPaymentRequest;
use App\Http\Requests\Admin\FeesCollection\SmRejectBankPaymentRequest;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;

class SmFeesBankPaymentController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function bankPaymentSlip()
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            return view('backEnd.feesCollection.bank_payment_slip', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function bankPaymentSlipSearch(SmFeesBankPaymentRequest $request)
    {
        try {
           
            $data = [];
            $data['date'] = $request->payment_date;
            $data['class_id'] = $request->class;
            $data['approve_status'] = $request->approve_status;
            $data['section_id'] = $request->section;
            $data['classes'] = SmClass::get();
            $data['sections'] = SmSection::get();
            
            if (moduleStatusCheck('University')) {
                $interface = App::make(UnCommonRepositoryInterface::class);
                $data += $interface->getCommonData($request);
            }  

            return view('backEnd.feesCollection.bank_payment_slip', $data);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function rejectFeesPayment(SmRejectBankPaymentRequest $request){

        try{
            $bank_payment = SmBankPaymentSlip::find($request->id);        
            $student = SmStudent::find($bank_payment->student_id);
            $parent = SmParent::find($student->parent_id);

            if($bank_payment){
                $bank_payment->reason = $request->payment_reject_reason;
                $bank_payment->approve_status = 2;
                $result = $bank_payment->save();

                if($result){
                    $notification = new SmNotification();
                    $notification->role_id = 2;
                    $notification->message ="Bank Payment Rejected -" .'('.@$bank_payment->feesType->name.')';
                    $notification->date = date('Y-m-d');
                    $notification->user_id = $student->user_id;
                    $notification->url = "student-fees";
                    $notification->school_id = Auth::user()->school_id;
                    $notification->academic_id = getAcademicId();
                    $notification->save();
                    Cache::forget('have_due_fees_'.@$student->user_id); 

                    try{
                        $receiver_email =  $student->full_name;
                        $receiver_name =   $student->email;
                        $subject= 'Bank Payment Rejected';
                        $view ="backEnd.feesCollection.bank_payment_reject_student";
                        $compact['data'] =  array( 
                                'note' => $bank_payment->reason, 
                                'date' =>dateConvert($notification->created_at),
                                'student_name' =>$student->full_name,
                        ); 
                        send_mail($receiver_email, $receiver_name, $subject , $view , $compact);
                   }catch(\Exception $e){
                       Log::info($e->getMessage());
                   }

                    $notification = new SmNotification();
                    $notification->role_id = 3;
                    $notification->message ="Bank Payment Rejected -" .'('.@$bank_payment->feesType->name.')';
                    $notification->date = date('Y-m-d');
                    $notification->user_id = $parent->user_id;
                    $notification->url = "parent-fees/".$student->id;
                    $notification->school_id = Auth::user()->school_id;
                    $notification->academic_id = getAcademicId();
                    $notification->save();

                    try{
                        $receiver_email =  $student->email;
                        $receiver_name =   $student->full_name;
                        $subject= 'Bank Payment Rejected';
                        $view ="backEnd.feesCollection.bank_payment_reject_student";
                        $compact['data'] =  array( 
                                'note' => $bank_payment->reason, 
                                'date' =>dateConvert($notification->created_at),
                                'student_name' =>$student->full_name,
                        ); 
                        send_mail($receiver_email, $receiver_name, $subject , $view , $compact);
                   }catch(\Exception $e){
                       Log::info($e->getMessage());
                   }

                }

                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            }
        }
        catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    
    public function approveFeesPayment(Request $request){
        try {
          if (checkAdmin()) {
                $bank_payment = SmBankPaymentSlip::find($request->id);
            }else{
                $bank_payment = SmBankPaymentSlip::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }

            if(moduleStatusCheck('University')){
                if(! is_null($bank_payment->child_payment_id)){
                    $childPayment = UnFeesInstallAssignChildPayment::find($bank_payment->child_payment_id);
                    $installment = UnFeesInstallmentAssign::find($childPayment->un_fees_installment_assign_id);
                    $due_payment =  ( discountFeesAmount($installment->id)  - $installment->paid_amount);
                    if($bank_payment->amount  > $due_payment ){
                        Toastr::warning('Due amount bigger than bank payment', 'Warning');
                        return redirect()->back();
                    }
                    $childPayment->active_status = 1;
                    $childPayment->paid_amount = $bank_payment->amount;
                    $childPayment->save();
                }else{
                    $installment = UnFeesInstallmentAssign::find($bank_payment->un_fees_installment_id);
                    $due_payment =  ( discountFeesAmount($installment->id)  - $installment->paid_amount);
                }
                if($bank_payment->amount  > $due_payment ){
                    Toastr::warning('Due amount bigger than bank payment', 'Warning');
                    return redirect()->back();
                }
                
                $payable_amount =  discountFeesAmount($installment->id);
                $total_paid =  $installment->paid_amount;
                $installment->paid_amount  =  $total_paid + $bank_payment->amount;
                if( $installment->paid_amount == $payable_amount){
                    $installment->active_status = 1;
                }else{
                    $installment->active_status = 2;  
                }
               
                $installment->created_by = Auth::user()->id;
                $installment->save();
            }

            elseif(directFees()){
                if(! is_null($bank_payment->child_payment_id)){
                    $childPayment = DireFeesInstallmentChildPayment::find($bank_payment->child_payment_id);
                    $installment = DirectFeesInstallmentAssign::find($bank_payment->installment_id);
                    $due_payment =  ( discountFees($installment->id)  - $installment->paid_amount);
                    if($bank_payment->amount  > $due_payment ){
                        Toastr::warning('Due amount bigger than bank payment', 'Warning');
                        return redirect()->back();
                    }
                    $childPayment->active_status = 1;
                    $childPayment->paid_amount = $bank_payment->amount;
                    $childPayment->save();
                }else{
                    $installment = DirectFeesInstallmentAssign::find($bank_payment->installment_id);
                    $due_payment =  ( discountFees($installment->id)  - $installment->paid_amount);
                }

                if($bank_payment->amount  > $due_payment ){
                    Toastr::warning('Due amount bigger than bank payment', 'Warning');
                    return redirect()->back();
                }
                
                $payable_amount =  discountFees($installment->id);
                $total_paid =  $installment->paid_amount;
                $installment->paid_amount  =  $total_paid + $bank_payment->amount;
                if( $installment->paid_amount == $payable_amount){
                    $installment->active_status = 1;
                }else{
                    $installment->active_status = 2;  
                }
               
                $installment->created_by = Auth::user()->id;
                $installment->save();
            }

            else{
                $get_master_id=SmFeesMaster::join('sm_fees_assigns','sm_fees_assigns.fees_master_id','=','sm_fees_masters.id')
                ->where('sm_fees_masters.fees_type_id',$bank_payment->fees_type_id)
                ->where('sm_fees_assigns.student_id',$bank_payment->student_id)->first();
    
                $fees_assign=SmFeesAssign::where('fees_master_id',$get_master_id->fees_master_id)
                            ->where('record_id',$bank_payment->record_id)
                            ->where('student_id',$bank_payment->student_id)
                            ->where('school_id',Auth::user()->school_id)
                            ->first();
                            if ($bank_payment->amount > $fees_assign->fees_amount) {
                                Toastr::warning('Due amount less than bank payment', 'Warning');
                                return redirect()->back();
                            }
            }

            $user = Auth::user();
            $fees_payment = new SmFeesPayment();
            $fees_payment->student_id = $bank_payment->student_id;
            $fees_payment->fees_type_id = $bank_payment->fees_type_id;
            $fees_payment->discount_amount = 0;
            $fees_payment->fine = 0;
            $fees_payment->amount = $bank_payment->amount;
            $fees_payment->assign_id = $bank_payment->assign_id;
            $fees_payment->payment_date = date('Y-m-d', strtotime($bank_payment->date));
            $fees_payment->payment_mode = $bank_payment->payment_mode;
            $fees_payment->bank_id= $bank_payment->payment_mode=='bank' ? $bank_payment->bank_id : null;
            $fees_payment->created_by = $user->id;
            $fees_payment->note = $bank_payment->note;
            $fees_payment->record_id = $bank_payment->record_id;
            
            $fees_payment->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $fees_payment->un_fees_installment_id =$bank_payment->un_fees_installment_id;
                $fees_payment->un_semester_label_id = $bank_payment->un_semester_label_id;
                $fees_payment->un_academic_id = getAcademicId();
                $fees_payment->installment_payment_id = $childPayment->id;
            }elseif(directFees()){
                $fees_payment->direct_fees_installment_assign_id = $bank_payment->installment_id;
                $fees_payment->academic_id = getAcademicId();
                $fees_payment->installment_payment_id = $childPayment->id;
            }
            else{
                $fees_payment->academic_id = getAcademicId();
            }
            $fees_payment->save();
            $bank_payment->approve_status = 1; 
            $bank_payment->save();
           


            $payment_mode_name= ucwords($bank_payment->payment_mode);
            $payment_method=SmPaymentMethhod::where('method',$payment_mode_name)->first();
            $income_head=generalSetting();

            $add_income = new SmAddIncome();
            $add_income->name = 'Fees Collect';
            $add_income->date = date('Y-m-d', strtotime($bank_payment->date));
            $add_income->amount = $bank_payment->amount;
            $add_income->fees_collection_id = $fees_payment->id;
            $add_income->active_status = 1;
            $add_income->income_head_id = $income_head->income_head_id;
            $add_income->payment_method_id = $payment_method->id;
            if($payment_method->id==3){
                $add_income->account_id = $bank_payment->bank_id;
            }
            $add_income->created_by = Auth()->user()->id;
            $add_income->school_id = Auth::user()->school_id;
            $add_income->academic_id = getAcademicId();
            $add_income->save();


            if($payment_method->id==3){
                $bank=SmBankAccount::where('id',$bank_payment->bank_id)
                ->where('school_id',Auth::user()->school_id)
                ->first();
                $after_balance= $bank->current_balance + $bank_payment->amount;

                $bank_statement= new SmBankStatement();
                $bank_statement->amount= $bank_payment->amount;
                $bank_statement->after_balance= $after_balance;
                $bank_statement->type= 1;
                $bank_statement->details= "Fees Payment";
                $bank_statement->payment_date= date('Y-m-d', strtotime($bank_payment->date));
                $bank_statement->bank_id= $bank_payment->bank_id;
                $bank_statement->school_id=Auth::user()->school_id;
                $bank_statement->payment_method= $payment_method->id;
                $bank_statement->fees_payment_id= $fees_payment->id;
                $bank_statement->save();

                $current_balance= SmBankAccount::find($bank_payment->bank_id);
                $current_balance->current_balance=$after_balance;
                $current_balance->update();
        }
            // $fees_assign=SmFeesAssign::where('fees_master_id',$get_master_id->fees_master_id)->where('student_id',$bank_payment->student_id)->first();
            if(moduleStatusCheck('University')){
               
            }
            elseif(directFees()){

            }
            else{
                $fees_assign->fees_amount-=$bank_payment->amount;
                $fees_assign->save();
            }
            

            $bank_slips = SmBankPaymentSlip::query();
            if(moduleStatusCheck('University')){
                if ($request->un_semester_label_id != "") {
                    $bank_slips->where('un_semester_label_id', $request->un_semester_label_id);
                }
            }else{
                $bank_slips->where('class_id', $request->class);
                if ($request->section != "") {
                    $bank_slips->where('section_id', $request->section);
                }
            }
            
            if ($request->payment_date != "") {
                $date = strtotime($request->payment_date);
                $new_format = date('Y-m-d', $date);

                $bank_slips->where('date', $new_format);
            }
            $bank_slips = $bank_slips->where('record_id',$bank_payment->record_id)
                            ->where('school_id',Auth::user()->school_id)
                            ->orderBy('id', 'desc')
                            ->get();
            $date = $request->payment_date;
            $class_id = $request->class;
            $section_id = $request->section;
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $sections = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $student = SmStudent::find($bank_payment->student_id);
            Cache::forget('have_due_fees_'.@$student->user_id); 
           
            try{
                $notification = new SmNotification;
                $notification->user_id = $student->user_id;
                $notification->role_id = 2;
                $notification->date = date('Y-m-d');
                $notification->message = app('translator')->get('fees.fees_approved');
                $notification->school_id = Auth::user()->school_id;
                $notification->academic_id = getAcademicId();
                $notification->save();
                $user=User::find($student->user_id);
               // Notification::send($user, new FeesApprovedNotification($notification));
               
            }catch (\Exception $e) {
                Log::info($e->getMessage());
            }

            try{
                $parent = SmParent::find($student->parent_id);
                Cache::forget('have_due_fees_'.@$parent->user_id); 
                $notification = new SmNotification();
                $notification->role_id = 3;
                $notification->message = app('translator')->get('fees.fees_approved_for_child');
                $notification->date = date('Y-m-d');
                $notification->user_id = $parent->user_id;
                $notification->url = "";
                $notification->school_id = Auth::user()->school_id;
                $notification->academic_id = getAcademicId();
                $notification->save();
                $user=User::find($parent->user_id);
              //  Notification::send($user, new FeesApprovedNotification($notification));
                }catch (\Exception $e) {
                    Log::info($e->getMessage());
                }
                
            Toastr::success('Operation successful', 'Success');
            return redirect('bank-payment-slip');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
