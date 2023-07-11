<?php


namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class YearCheck extends Model
{
    public static function getYear()
    {
        try {
            $year = generalSetting();
            if(moduleStatusCheck('University')){
                return  $year->unacademic_Year->created_at->format('Y');
            }else{
                return $year->academic_Year->year;
            }
            
        } catch (\Exception $e) {
            return date('Y');
        }
    }
    public static function getAcademicId()
    {
        try {
            $year = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            return $year->session_id;
        } catch (\Exception $e) {
            return "1";
        }
    }
    public static function AcStartDate()
    {
        try { 
            $start_date = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first(); 
            return $start_date->academic_Year->starting_date;
        } catch (\Exception $e) {
            return date('Y');
        }
    }
    public static function AcEndDate()
    {
        try { 
            $end_date = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first(); 
            return $end_date->academic_Year->ending_date;
        } catch (\Exception $e) {
            return date('Y');
        }
    }
}
