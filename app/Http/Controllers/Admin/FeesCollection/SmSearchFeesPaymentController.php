<?php

namespace App\Http\Controllers\Admin\FeesCollection;

use App\SmClass;
use App\SmFeesAssign;
use App\SmFeesMaster;
use App\ApiBaseMethod;
use App\SmBankAccount;
use App\SmFeesPayment;
use App\SmPaymentMethhod;
use App\Models\FeesInvoice;
use Illuminate\Http\Request;
use App\SmPaymentGatewaySetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\FeesCollection\SmFeesCollectSearchRequest;

class SmSearchFeesPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index(Request $request)
    {
        try {
            if(auth()->user()->role_id ==1 || auth()->user()->role_id ==5){
                $fees_payments = SmFeesPayment::with('recordDetail','installmentPayment','recordDetail.studentDetail','recordDetail.class','recordDetail.section')
                                ->where('active_status',1)
                                ->where('school_id', auth()->user()->school_id)
                                ->orderby('id','DESC')
                                ->whereNotNull('installment_payment_id')
                                ->get();
            }else{
                $fees_payments = SmFeesPayment::with('recordDetail','installmentPayment','recordDetail.studentDetail','recordDetail.class','recordDetail.section')
                                ->where('created_by',auth()->user()->id)
                                ->where('school_id', auth()->user()->school_id)
                                ->where('active_status',1)
                                ->orderby('id','DESC')
                                ->whereNotNull('installment_payment_id')
                                ->get();
            }
            $classes = SmClass::where('active_status', 1)
                        ->where('school_id',Auth::user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();
            $invoice_setting = FeesInvoice::where('school_id', auth()->user()->school_id)->first(['prefix','start_form']);
            return view('backEnd.feesCollection.search_fees_payment', compact('classes','fees_payments','invoice_setting'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function search(SmFeesCollectSearchRequest $request)
    {      
     
        $date_from = date('Y-m-d', strtotime($request->date_from));
        $date_to = date('Y-m-d', strtotime($request->date_to));
        try {
            $classes = SmClass::where('active_status', 1)
                        ->where('school_id',Auth::user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();

            $search['date_from']  = $request->date_from;
            $search['date_to']    = $request->date_to;
            $search['class']  = $request->class;
            $search['section']  = $request->section;
            $search['keyword']  = $request->keyword;
            $invoice_setting = FeesInvoice::where('school_id', auth()->user()->school_id)->first(['prefix','start_form']);
            return view('backEnd.feesCollection.search_fees_payment', compact('classes','invoice_setting'))->with($search);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editFeesPayment($id){
        try {
            $fees_payment = SmFeesPayment::find($id);
            if(auth()->user()->role_id !=1){
                if($fees_payment->created_by !=  auth()->user()->id ){
                    Toastr::error('Payment recieved Other person,You Can not Edit', 'Failed');
                    return redirect()->back();
                }
            }
            $data['bank_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Bank')
                                ->where('school_id', Auth::user()->school_id)
                                ->first();
            $data['cheque_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Cheque')
                                ->where('school_id', Auth::user()->school_id)
                                ->first();

            $banks = SmBankAccount::where('school_id', Auth::user()->school_id)->get();

            $method['bank_info'] = SmPaymentMethhod::where('method', 'Bank')
                                ->where('school_id', Auth::user()->school_id)
                                ->first();

            $method['cheque_info'] = SmPaymentMethhod::where('method', 'Cheque')
                                ->where('school_id', Auth::user()->school_id)
                                ->first();
            return view('backEnd.feesCollection.edit_fees_payment_modal', compact('fees_payment','data','method','banks'));
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function updateFeesPayment(Request $request){

        try {

            $assignCourseFees=SmFeesAssign::find($request->fees_assign_id);
            $fees_master = SmFeesMaster::find($assignCourseFees->fees_master_id);
            $amount_check = $assignCourseFees->fees_amount - $request->amount;

            if( $fees_master->amount <= $request->amount  ){
                Toastr::warning('Payment amount will not greater than fees assign amount', 'Warning');
                return redirect()->back();
            }elseif( $amount_check < 0){
                $payment=SmFeesPayment::find($request->fees_payment_id);
                $payment->payment_mode = $request->payment_mode;
                $payment->bank_id= $request->payment_mode=='bank' ? $request->bank_id : null;
                $payment->save();
                Toastr::warning('Fees Payment already full paid, Can not Change Amount', 'Warning');
                return redirect()->back();

            }

            if($assignCourseFees->fees_amount==0){

                $pre_amount = $assignCourseFees->fees_amount;

            }else{

                $diff_amount=$request->amount-$request->pre_amount;


                if($diff_amount > 0 ){

                    $pre_amount = $assignCourseFees->fees_amount-$diff_amount;


                }else{

                    $pre_amount = $assignCourseFees->fees_amount-($diff_amount);

                }

            }



            $assignCourseFees->fees_amount=$pre_amount;
            $result= $assignCourseFees->save();
            if($result){
                $payment=SmFeesPayment::find($request->fees_payment_id);
                $payment->amount=$request->amount;
                $payment->payment_mode = $request->payment_mode;
                $payment->bank_id= $request->payment_mode=='bank' ? $request->bank_id : null;
                $payment->save();
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }



            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

}
