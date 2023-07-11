<?php

namespace App\Models;

use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllExamWisePosition extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected static function boot()
    {
        parent::boot();
  
        return static::addGlobalScope(new AcademicSchoolScope);
    }
}
