<?php

namespace App;

use App\Models\DirectFeesInstallment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\StatusAcademicSchoolScope;

class SmFeesMaster extends Model
{
    use HasFactory;
    protected $fillable= ['fees_group_id', 'fees_type_id', 'date', 'amount', 'un_semester_label_id', 'academic_id', 'school_id','un_subject_id','un_academic_id'];
	protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StatusAcademicSchoolScope);
    }
    
    
    public function feesTypes()
    {
        return $this->belongsTo('App\SmFeesType', 'fees_type_id');
    }

    public function feesType()
    {
        return $this->belongsTo('App\SmFeesType', 'fees_type_id','id');
    }

    public function feesGroups()
    {
        return $this->belongsTo('App\SmFeesGroup', 'fees_group_id', 'id');
    }

    public function feesTypeIds()
    {
        return $this->hasMany('App\SmFeesMaster', 'fees_group_id', 'fees_group_id');
    }

    
    public function installments()
    {
        return $this->hasMany(DirectFeesInstallment::class,'fees_master_id','id');
    }

    public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id','id');
    }
}
