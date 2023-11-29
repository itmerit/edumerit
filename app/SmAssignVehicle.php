<?php

namespace App;

use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmAssignVehicle extends Model
{
    // protected static function boot()
    // {
    //     parent::boot();
  
    //     static::addGlobalScope(new ActiveStatusSchoolScope);
    // }
    use HasFactory;
    public function route(){
    	return $this->belongsTo('App\SmRoute', 'route_id', 'id');
    }
    public function vehicle(){
    	return $this->belongsTo('App\SmVehicle', 'vehicle_id', 'id');
    }
}
