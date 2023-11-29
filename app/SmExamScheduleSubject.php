<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmExamScheduleSubject extends Model
{
    use HasFactory;
    public function subject(){
    	return $this->belongsTo('App\SmSubject', 'subject_id', 'id');
    }
}
