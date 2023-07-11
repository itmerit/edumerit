<?php

namespace App\PaymentGateway;

use App\User;
use App\SmParent;
use App\SmStudent;
use App\SmFeesAssign;
use App\SmFeesMaster;
use Razorpay\Api\Api;
use App\SmFeesPayment;
use App\SmPaymentGatewaySetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Entities\WalletAddMoney;
use Modules\Wallet\Entities\WalletTransaction;

class RazorPayPayment
{
    public function handle($data)
    {

        if ($data['type'] == "Wallet") {
            $user = User::find($data['user_id']);
            $currentBalance = $user->wallet_balance;
            $user->wallet_balance = $currentBalance + $data['amount'];
            $user->update();

            $addPayment = new WalletTransaction();
            $addPayment->amount= $data['amount'] - gv($data, 'service_charge', 0);
            $addPayment->payment_method = "RazorPay";
            $addPayment->user_id = $user->id;
            $addPayment->type = $data['wallet_type'];
            $addPayment->school_id = Auth::user()->school_id;
            $addPayment->academic_id = getAcademicId();
            $addPayment->status = 'approve';
            $addPayment->save();

            $gs = generalSetting();
            $compact['full_name'] = $user->full_name;
            $compact['method'] = $addPayment->payment_method;
            $compact['create_date'] = date('Y-m-d');
            $compact['school_name'] = $gs->school_name;
            $compact['current_balance'] = $user->wallet_balance;
            $compact['add_balance'] = $data['amount'];
            @send_mail($user->email, $user->full_name, "wallet_approve", $compact);
        }

    }
}