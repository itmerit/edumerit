<?php

namespace App;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmItemReceiveChild extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
   
    public function items(){
    	return $this->belongsTo('App\SmItem', 'item_id', 'id');
    }
}
