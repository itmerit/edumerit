<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmNotificationSetting extends Model
{
    use HasFactory;

    protected $casts= [ 'destination' => 'array', 'template' => 'array', 'subject' => 'array', 'recipient' => 'array'];
}
