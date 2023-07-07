<?php

namespace App;

use App\SmParent;
use App\SmStaff;
use App\SmStudent;
use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmBook extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new ActiveStatusSchoolScope);
    }
    
    
    public function bookCategory()
    {
        return $this->belongsTo('App\SmBookCategory', 'book_category_id', 'id')->withDefault();
    }

    public function bookSubject()
    {
        return $this->belongsTo('App\LibrarySubject', 'book_subject_id', 'id')->withDefault();
    }

    public static function getMemberDetails($memberID)
    {

        try {
            return SmStudent::select('full_name', 'email', 'mobile')->where('id', '=', $memberID)->first();
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function getMemberStaffsDetails($memberID)
    {

        try {
            return SmStaff::select('full_name', 'email', 'mobile')->where('user_id', '=', $memberID)->first();
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function getParentDetails($memberID)
    {

        try {
            return $getMemberDetails = SmParent::select('full_name', 'email', 'mobile')->where('user_id', '=', $memberID)->first();
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

}
