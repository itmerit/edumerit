<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmNews extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo('App\SmNewsCategory');
    }

    public function scopeMissions($q)
    {
        return $q->whereHas('category', function($q){
            
            return $q->where('type', 'mission');
         
        });
    }

    public function scopeHistories($q)
    {
        return $q->whereHas('category', function($q){
            
            return $q->where('type', 'history');
         
        });
    }
}
