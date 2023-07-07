<?php

namespace Modules\Fees\Entities;

use Modules\Fees\Entities\FmFeesType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FmFeesInvoiceChield extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Fees\Database\factories\FmFeesInvoiceChieldFactory::new();
    }

    public function feesType()
    {
        return $this->belongsTo(FmFeesType::class,'fees_type','id');
    }
}
