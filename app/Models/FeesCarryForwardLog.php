<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeesCarryForwardLog extends Model
{
    use HasFactory;

    public function studentRecord()
    {
        return $this->belongsTo('App\Models\StudentRecord', 'student_record_id', 'id')->withDefault();
    }
}
