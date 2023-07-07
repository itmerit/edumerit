<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmCurrency extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function active()
    {
        return $this->hasOne(SmGeneralSettings::class, 'currency', 'code');
    }
    public function getTypeAttribute()
    {
        if ($this->currency_type == 'S') {
            return __('system_settings.symbol');
        } elseif($this->currency_type == 'C') {
            return __('system_settings.code');
        } else {
            return  __('common.not found');
        }       
    }
    public function getPositionAttribute()
    {
        if ($this->currency_position == 'S') {
            return __('system_settings.suffix');
        } elseif($this->currency_position == 'P') {
            return __('system_settings.prefix');
        } else {
            return  __('common.not found');
        } 
        
    }
}
