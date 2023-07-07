<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmExamSetup extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function class(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }
    public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }

    public function subjectDetails()
    {
        return $this->belongsTo('App\SmSubject', 'subject_id', 'id');
    }

    public function unSubject()
    {
        return $this->belongsTo('Modules\University\Entities\UnSubject', 'un_subject_id', 'id');
    }
}
