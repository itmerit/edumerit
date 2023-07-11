<?php

namespace Modules\ExamPlan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeatPlanSetting extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ExamPlan\Database\factories\SeatPlanSettingFactory::new();
    }
}
