<?php

use App\SmPaymentGatewaySetting;


if(!function_exists('service_charge')) {
    function service_charge($charge_type = null, $charge = null) {  
        $serviceCharge = null;
        if($charge_type == "P"){
            $serviceCharge = $charge.'(%)';
        }elseif($charge_type == "F"){
            $serviceCharge = currency_format($charge) ;
        }
        return $serviceCharge; 
    }
}


if(!function_exists('serviceCharge')) {
    function serviceCharge(string $gateway) {      
        $gatewaySettings = SmPaymentGatewaySetting::where('gateway_name', $gateway)->where('school_id', auth()->user()->school_id)->first();
       
        $serviceCharge = null;
        if ($gatewaySettings && $gatewaySettings->service_charge) {
            if ($gatewaySettings->charge_type == "P") {
                $serviceCharge = $gatewaySettings->charge.'(%)';
            }elseif ($gatewaySettings->charge_type == "F") {
                $serviceCharge = currency_format($gatewaySettings->charge) ;
            }
        }
        return $serviceCharge;
    }
}
if(!function_exists('chargeAmount')) {
    function chargeAmount(string $gateway, $amount) {      
        $gatewaySettings = SmPaymentGatewaySetting::where('gateway_name', $gateway)->where('school_id', auth()->user()->school_id)->first();
        $chargeAmount = 0;
        if ($gatewaySettings && $gatewaySettings->service_charge && $amount) {
            if ($gatewaySettings->charge_type == "P") {
                $chargeAmount = number_format(($gatewaySettings->charge / 100) * $amount, 2, '.','');
            }elseif ($gatewaySettings->charge_type == "F") {
                $chargeAmount = number_format($gatewaySettings->charge, 2,'.','');
            }
        }
        return $chargeAmount;
    }
}
if(!function_exists('serviceChargeWithTotal')) {
    function serviceChargeWithTotal(string $gateway, $amount = null) {    
        $charge  = 0;
        $gatewaySettings = SmPaymentGatewaySetting::where('gateway_name', $gateway)->where('school_id', auth()->user()->school_id)->first();
        if ($gatewaySettings && $gatewaySettings->service_charge == 1  && $amount) {
            if ($gatewaySettings->charge_type == "P") {
                $charge = ($gatewaySettings->charge / 100) * $amount;
            }elseif ($gatewaySettings->charge_type == "F") {
                $charge =  $gatewaySettings->charge;
            }
        }
        return currency_format($amount + $charge);
    }
}