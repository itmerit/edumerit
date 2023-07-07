<?php

namespace Modules\Fees\Entities;

use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FmFeesTransaction extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new AcademicSchoolScope);
    }
    
    protected static function newFactory()
    {
        return \Modules\Fees\Database\factories\FmFeesTransactionFactory::new();
    }

    public function transcationDetails()
    {
        return $this->hasMany('Modules\Fees\Entities\FmFeesTransactionChield','fees_transaction_id');
    }

    public function getPaidAmountAttribute()
    {
        return $this->transcationDetails()->sum('paid_amount');
    }

    public function getFineAttribute()
    {
        return $this->transcationDetails()->sum('fine');
    }

    public function getWeaverAttribute()
    {
        return $this->transcationDetails()->sum('weaver');
    }

    public function getNoteAttribute()
    {
        return $this->transcationDetails()->first('note')->note;
    }

    public function feesInvoiceInfo()
    {
        return $this->belongsTo(FmFeesInvoice::class,'fees_invoice_id','id');
    }

    public function feeStudentInfo()
    {
        return $this->belongsTo('App\SmStudent', 'student_id', 'id');
    }

    public function recordDetail()
    {
        return $this->belongsTo('App\Models\StudentRecord', 'record_id', 'id');
    }
}
