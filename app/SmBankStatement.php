<?php

namespace App;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmBankStatement extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
    public function bankName()
    {
        return $this->belongsTo('App\SmBankAccount', 'bank_id', 'id');
    }

    public function paymentMethod(){
        return $this->belongsTo('App\SmPaymentMethhod','payment_method','id');
    }
    
}
