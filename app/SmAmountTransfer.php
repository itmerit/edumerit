<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmAmountTransfer extends Model
{
    use HasFactory;
    public function fromPaymentMethodName(){
        return $this->belongsTo('App\SmPaymentMethhod','from_payment_method','id');
    }

    public function toPaymentMethodName(){
        return $this->belongsTo('App\SmPaymentMethhod','to_payment_method','id');
    }
}
