<?php

namespace App;


use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmAboutPage extends Model
{
    protected static function boot()
    {
        parent::boot();
  
        return static::addGlobalScope(new ActiveStatusSchoolScope);
    }
    use HasFactory;
}
