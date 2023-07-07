<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\StatusAcademicSchoolScope;

class SmQuestionBank extends Model
{
    use HasFactory;
	protected static function boot(){
        parent::boot();
        static::addGlobalScope(new StatusAcademicSchoolScope);
    }
    
    
    public function questionGroup()
    {
        return $this->belongsTo('App\SmQuestionGroup', 'q_group_id')->withOutGlobalScope(StatusAcademicSchoolScope::class);
    }
    public function questionLevel()
    {
        return $this->belongsTo('App\SmQuestionLevel', 'question_level_id');
    }
    public function class()
    {
        return $this->belongsTo('App\SmClass', 'class_id', 'id')->withOutGlobalScope(StatusAcademicSchoolScope::class);
    }
    public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id')->withOutGlobalScope(StatusAcademicSchoolScope::class);
    }
    public function questionMu()
    {
        return $this->hasMany('App\SmQuestionBankMuOption', 'question_bank_id', 'id');
    }
}
