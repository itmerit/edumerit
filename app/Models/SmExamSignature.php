<?php

namespace App\Models;

use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmExamSignature extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'signature', 'active_status', 'school_id', 'academic_id'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AcademicSchoolScope);
    }
}
