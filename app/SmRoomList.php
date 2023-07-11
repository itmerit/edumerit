<?php

namespace App;

use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmRoomList extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new ActiveStatusSchoolScope);
    }
   
    public function dormitory()
    {
        return $this->belongsTo('App\SmDormitoryList', 'dormitory_id');
    }

    public function roomType()
    {
        return $this->belongsTo('App\SmRoomType', 'room_type_id');
    }
}
