<?php

namespace App\Http\Controllers;

use App\SmPaymentMethhod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class GatewayPaymentController extends Controller
{
    public function makeFeesPayment(Request $request){
        
        $input = $request->all();

        $validator = Validator::make($input, [
            'fees_type_id' => "required",
            'payment_method'=>"required",
            'amount' => "required",
            'assign_id' => "required", 
            'student_id' => "required",
        ]);
        

        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }
        
        $data = [];

        try{
            $data['fees_type_id'] = $request->fees_type_id;
            $data['real_amount'] =$request->real_amount;
            $data['amount'] = $request->amount;
            $data['assign_id'] = $request->assign_id;
            $data['student_id'] = $request->student_id;
            $data['method'] = SmPaymentMethhod::find($request->payment_method)->method;
            $classMap = config('paymentGateway.'.$data['method']);
            $make_payment = new $classMap();
           return $make_payment->handle($data);
        }catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function makeWalletPayment(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'payment_method'=>"required",
            'amount' => "required",
            'student_id' => "required",
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }
        
        $data = [];

        try{
            $data['fees_type_id'] = $request->fees_type_id;
            $data['real_amount'] =$request->real_amount;
            $data['amount'] = $request->amount;
            $data['assign_id'] = $request->assign_id;
            $data['student_id'] = $request->student_id;
            $data['method'] = SmPaymentMethhod::find($request->payment_method)->method;
            $classMap = config('paymentGateway.'.$data['method']);
            $make_payment = new $classMap();
            $make_payment->handle($data);
        }catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
