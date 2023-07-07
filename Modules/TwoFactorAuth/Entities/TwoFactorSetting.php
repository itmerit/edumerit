<?php

namespace Modules\TwoFactorAuth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TwoFactorSetting extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\TwoFactorAuth\Database\factories\TwoFactorSettingFactory::new();
    }
}
