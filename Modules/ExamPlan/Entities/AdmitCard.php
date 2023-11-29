<?php

namespace Modules\ExamPlan\Entities;

use App\Models\StudentRecord;
use App\SmExamType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmitCard extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ExamPlan\Database\factories\AdmitCardFactory::new();
    }

    public function studentRecord(){
        return $this->belongsTo(StudentRecord::class,'student_record_id','id');
    }

    public function examType(){
        return $this->belongsTo(SmExamType::class,'exam_type_id','id');
    }
}
