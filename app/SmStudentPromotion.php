<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmStudentPromotion extends Model
{
    public function student(){
        return $this->belongsTo('App\SmStudent', 'student_id', 'id');
    }

    public function class(){
		return $this->belongsTo('App\SmClass', 'previous_class_id', 'id');
    }
    
}
