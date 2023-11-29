<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireFeesInstallmentChildPayment extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    
    public function installmentAssign()
    {
        return $this->belongsTo(DirectFeesInstallmentAssign::class, 'direct_fees_installment_assign_id', 'id');
    }

    
}
