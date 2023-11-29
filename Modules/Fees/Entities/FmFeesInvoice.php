<?php

namespace Modules\Fees\Entities;

use App\SmStudent;
use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Model;
use Modules\Fees\Entities\FmFeesInvoiceChield;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FmFeesInvoice extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new AcademicSchoolScope);
    }

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Fees\Database\factories\FmFeesInvoiceFactory::new();
    }

    public function studentInfo()
    {
        return $this->belongsTo(SmStudent::class,'student_id','id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(FmFeesInvoiceChield::class,'fees_invoice_id');
    }

    public function getTamountAttribute()
    {
        return $this->invoiceDetails()->sum('amount');
    }

    public function getTweaverAttribute()
    {
        return $this->invoiceDetails()->sum('weaver');
    }

    public function getTfineAttribute()
    {
        return $this->invoiceDetails()->sum('fine');
    }

    public function getTpaidamountAttribute()
    {
        return $this->invoiceDetails()->sum('paid_amount');
    }

    public function getTsubtotalAttribute()
    {
        return $this->invoiceDetails()->sum('sub_total');
    }

    public function recordDetail(){
        return $this->belongsTo('App\Models\StudentRecord', 'record_id', 'id');
    }
}
