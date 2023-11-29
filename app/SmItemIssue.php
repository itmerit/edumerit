<?php

namespace App;

use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmItemIssue extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new AcademicSchoolScope);
    }
    
    public function items(){
    	return $this->belongsTo('App\SmItem', 'item_id', 'id');
    }

    public function categories(){
    	return $this->belongsTo('App\SmItemCategory', 'item_category_id', 'id');
    }
    
}
