<?php

namespace App\Http\Controllers\api;

use App\SmAddIncome;
use App\SmFeesPayment;
use App\SmAcademicYear;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SmPaymentGatewayController extends Controller
{
    public function dataSave(Request $request){

        $fees_payment = new SmFeesPayment();
        $fees_payment->student_id = $request->student_id;
        $fees_payment->fees_type_id =$request->fees_type_id;
        $fees_payment->discount_amount = 0;
        $fees_payment->fine = 0;
        $fees_payment->amount = $request->amount;
        $fees_payment->assign_id = $request->assign_id;
        $fees_payment->payment_date = date('Y-m-d', strtotime(date('Y-m-d')));
        $fees_payment->payment_mode = $request->method;
        $fees_payment->school_id = $request->school_id;
        $fees_payment->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        $result = $fees_payment->save();
        if($result){
            return response()->json(['payment_ref' => $fees_payment->id], 200);
        }
    }

    public function successCallback(Request $request){

        if($request->payment_ref && $request->status){
            $fees_payment = SmFeesPayment::find($request->payment_ref);
            if( $fees_payment){
                $fees_payment->active_status = 1 ; 
                $fees_payment->save();

                $gs = SmGeneralSettings::first('income_head_id');

                $add_income = new SmAddIncome();
                $add_income->name = 'Fees Collect';
                $add_income->date = date('Y-m-d', strtotime(date('Y-m-d')));
                $add_income->amount = $fees_payment->amount;
                $add_income->fees_collection_id = $fees_payment->id;
                $add_income->active_status = 1;
                $add_income->income_head_id = $gs->income_head_id;
                $add_income->payment_method_id = 4;
                $add_income->created_by = Auth()->user()->id;
                $add_income->school_id = Auth::user()->school_id;
                $add_income->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                $add_income->save();

                return response()->json(['message' => "Payment successfully completed"], 200);
            }
        }

    }
}
