<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentGatewayCallbackController extends Controller
{
    public function successCallback($method)
    { 
        $classMap = config('paymentGateway.'.$method);
        
        $new_payment = new $classMap();
        return $new_payment->successCallback();
    }

    public function cancelCallback($method){

        $classMap = config('paymentGateway.'.$method);

        $new_payment = new $classMap();
        return $new_payment->cancelCallBack();
    }
}
