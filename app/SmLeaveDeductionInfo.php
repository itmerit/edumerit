<?php

namespace App;

use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmLeaveDeductionInfo extends Model
{
    use HasFactory;
    public static function boot()
    {
        parent::boot();
		static::addGlobalScope(new AcademicSchoolScope);
    }
}
