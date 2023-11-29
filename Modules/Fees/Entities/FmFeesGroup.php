<?php

namespace Modules\Fees\Entities;

use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FmFeesGroup extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new AcademicSchoolScope);
    }

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Fees\Database\factories\FmFeesGroupFactory::new();
    }

    public function feesTypes(){
        return $this->hasMany(FmFeesType::class,'fees_group_id');
    }

    public function feesTypeNames(){
        return $this->hasMany(FmFeesType::class,'fees_group_id')->select(['name']);
    }
}
