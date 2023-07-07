<?php

namespace App;


use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmBookCategory extends Model
{
    use HasFactory;

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new AcademicSchoolScope);
    }
    
    public function scopeStatus($query){
        return $query->where('school_id',auth()->user()->school_id);
    }
   
}
