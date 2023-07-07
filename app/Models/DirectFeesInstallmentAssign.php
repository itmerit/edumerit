<?php

namespace App\Models;

use App\User;
use App\Models\DirectFeesInstallment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DirectFeesInstallmentAssign extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function installment(){
        return $this->belongsTo(DirectFeesInstallment::class,'fees_installment_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function payments()
    {
        return $this->hasMany(DireFeesInstallmentChildPayment::class, 'direct_fees_installment_assign_id', 'id')->where('active_status',1);
    }

    public function recordDetail()
    {
        return $this->belongsTo(StudentRecord::class,'record_id','id')->withDefault();
    }

}
