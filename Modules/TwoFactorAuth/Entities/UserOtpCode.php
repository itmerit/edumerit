<?php

namespace Modules\TwoFactorAuth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserOtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp_code',
        'expired_time'
    ];
    
    protected static function newFactory()
    {
        return \Modules\TwoFactorAuth\Database\factories\UserOtpCodeFactory::new();
    }
}
