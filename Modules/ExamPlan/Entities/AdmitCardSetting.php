<?php

namespace Modules\ExamPlan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmitCardSetting extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ExamPlan\Database\factories\AdmitCardSettingFactory::new();
    }
}
