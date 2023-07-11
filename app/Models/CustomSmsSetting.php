<?php

namespace App\Models;

use App\SmSmsGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomSmsSetting extends Model
{
    use HasFactory; 
    public function smsGateway()
        {
            return $this->belongsTo('App\SmSmsGateway', 'gateway_id', 'id');
        }
   
}
