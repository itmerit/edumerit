<?php

namespace App;

use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SmVehicle extends Model
{
    use HasFactory;
  // protected static function boot()
  // {
  //     parent::boot();
  //     static::addGlobalScope(new ActiveStatusSchoolScope);
  // } 
    
    public function driver()
    {
        return $this->belongsTo("App\SmStaff", "driver_id", "id");
    }

    public static function findVehicle($id)
    {
        try {
            return SmVehicle::find($id);
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
    public function scopeStatus($query)
    {        
        return $query->where('school_id', auth()->user()->id);
    }
}
