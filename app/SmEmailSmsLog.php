<?php

namespace App;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;

class SmEmailSmsLog extends Model
{
    use HasFactory;

    public  static function saveEmailSmsLogData($request){

    	$selectTabb = '';
        if(empty($request->selectTab)){
            $selectTabb = 'G';
        }
        else{
            $selectTabb = $request->selectTab;
        }

        $emailSmsData = new SmEmailSmsLog();
        $emailSmsData->title = $request->email_sms_title;
        $emailSmsData->description = $request->description;
        $emailSmsData->send_through = $request->send_through;
        $emailSmsData->send_date = date('Y-m-d');
        $emailSmsData->send_to = $selectTabb;
        $emailSmsData->school_id =Auth::user()->school_id;
        $emailSmsData->academic_id =getAcademicId();
        $success = $emailSmsData->save();
    }

    public  static function un_saveEmailSmsLogData($request)
    {
        $emailSmsData = new SmEmailSmsLog();
        $emailSmsData->title = $request->email_sms_title;

        $common = App::make(UnCommonRepositoryInterface::class);
        $common->storeUniversityData($emailSmsData, $request);
        
        $emailSmsData->description = $request->description;
        $emailSmsData->send_through = $request->send_through;
        $emailSmsData->send_date = date('Y-m-d');
        $emailSmsData->send_to = $request->selectTab;
        $emailSmsData->school_id =Auth::user()->school_id;
        $success = $emailSmsData->save();
    }

    public function sessionDetails()
    {
        return $this->belongsTo('Modules\University\Entities\UnSession', 'un_session_id', 'id')->withDefault();
    }

    public function semesterDetails()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemester', 'un_semester_id', 'id')->withDefault();
    }

    public function academicYearDetails()
    {
        return $this->belongsTo('Modules\University\Entities\UnAcademicYear', 'un_academic_id', 'id')->withDefault();
    }

    public function departmentDetails()
    {
        return $this->belongsTo('Modules\University\Entities\UnDepartment', 'un_department_id', 'id')->withDefault();
    }

    public function facultyDetails()
    {
        return $this->belongsTo('Modules\University\Entities\UnFaculty', 'un_faculty_id', 'id')->withDefault();
    }

    public function semesterLabelDetails()
    {
        return $this->belongsTo('Modules\University\Entities\UnsemesterLabel', 'un_semester_label_id', 'id')->withDefault();
    }

    public function sectionDetails()
    {
        return $this->belongsTo('App\SmSection', 'un_semester_label_id', 'id')->withDefault();
    }


}
