<?php

namespace Modules\BulkPrint\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeesInvoiceSetting extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\BulkPrint\Database\factories\FeesInvoiceSettingFactory::new();
    }
}
