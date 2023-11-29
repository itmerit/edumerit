<?php

namespace App;

use App\Scopes\SchoolScope;
use App\SmInventoryPayment;
use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmItemReceive extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
    
    public function suppliers(){
    	return $this->belongsTo('App\SmSupplier', 'supplier_id', 'id');
    }

    public function paymentMethodName(){
        return $this->belongsTo('App\SmPaymentMethhod','payment_method','id');
    }

    public function bankName(){
        return $this->belongsTo('App\SmBankAccount','account_id','id');
    }

    public function itemPayments(){
        return $this->hasMany(SmInventoryPayment::class,'item_receive_sell_id','id');
    }

}
