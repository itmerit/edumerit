<?php

namespace Modules\Fees\Entities;

use App\Scopes\AcademicSchoolScope;
use Modules\Fees\Entities\FmFeesGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FmFeesType extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new AcademicSchoolScope);
    }

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Fees\Database\factories\FmFeesTypeFactory::new();
    }

    public function fessGroup(){
        return $this->belongsTo(FmFeesGroup::class,'fees_group_id','id');
    }
}
