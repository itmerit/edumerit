<?php

namespace App\Models;

use App\SmAddExpense;
use App\SmBankStatement;
use App\SmPaymentMethhod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollPayment extends Model
{
    use HasFactory;
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }
    public function expenseDetail()
    {
        return $this->belongsTo(SmAddExpense::class, 'payroll_payment_id', 'id');
    }
    public function bankStatementDetail()
    {
        return $this->belongsTo(SmBankStatement::class, 'payroll_payment_id', 'id');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(SmPaymentMethhod::class, 'payment_mode', 'id')->withDefault();
    }
}
