<?php

namespace App;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmInventoryPayment extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
    
    public function paymentMethods(){
    	return $this->belongsTo('App\SmPaymentMethhod', 'payment_method', 'id');
    }

   
    public  static function itemPaymentdetails($item_receive_id){
    	
        try {
            $itemPaymentdetails = SmInventoryPayment::where('item_receive_sell_id', '=', $item_receive_id)->get();
            return count($itemPaymentdetails);
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }
}
