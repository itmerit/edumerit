<?php

namespace App;

use App\Models\StudentRecord;
use App\Scopes\AcademicSchoolScope;
use Modules\Alumni\Entities\Alumni;
use Modules\Alumni\Entities\Graduate;
use Illuminate\Database\Eloquent\Model;
use Modules\Lms\Entities\LessonComplete;
use Modules\Lms\Entities\CoursePurchaseLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmStudentCertificate extends Model
{   
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
    
    }

    public function roleName()
    {
        return $this->belongsTo('Modules\RolePermission\Entities\InfixRole','role','id');
    }

    public static function certificateBody($body, $role, $student_id, $cer_id=null)
    { 
       
        try {
            if ($role==2) {
                $student = SmStudent::find($student_id);
               
                $alumni = null;
                $studentRecord = '';
                if ($student) {
                    $studentRecord = StudentRecord::where('student_id', $student->id)->where('school_id', auth()->user()->school_id)->first();
                }
               
                $body = str_replace('[name]', @$student->full_name, $body);
                $body = str_replace('[dob]', dateConvert($student->date_of_birth), $body);
                $body = str_replace('[present_address]', @$student->current_address, $body);
                $body = str_replace('[guardian]', @$student->parents->guardians_name, $body);
                $body = str_replace('[created_at]', @$student->created_at, $body);
                $body = str_replace('[admission_no]', @$student->admission_no, $body);
                $body = str_replace('[roll_no]', @$studentRecord->roll_no, $body);
                $body = str_replace('[class]', @$studentRecord->class->class_name, $body);
                $body = str_replace('[section]', @$studentRecord->section->section_name, $body);
                $body = str_replace('[gender] ', @$student->gender->base_setup_name, $body);
                $body = str_replace('[admission_date]', @$student->admission_date, $body);
                $body = str_replace('[category]', @$student->category->category_name, $body);
                $body = str_replace('[cast]', @$student->caste, $body);
                $body = str_replace('[father_name]', @$student->parents->fathers_name, $body);
                $body = str_replace('[mother_name]', @$student->parents->mothers_name, $body);
                $body = str_replace('[religion]', @$student->religion->base_setup_name, $body);
                $body = str_replace('[email]', @$student->email, $body);
                $body = str_replace('[phone]', @$student->mobile, $body);
                $body = str_replace('[profile_image]', @$student->full_name, $body);
                if(moduleStatusCheck('University')){
                    $body = str_replace('[faculty]', @$studentRecord->unFaculty->name, $body);
                   
                    $body = str_replace('[session]', @$studentRecord->unSession->name, $body);
                    $body = str_replace('[department]', @$studentRecord->unDepartment->name, $body);
                    $body = str_replace('[academic]', @$studentRecord->unAcademic->name, $body);
                    $body = str_replace('[semester]', @$studentRecord->unSemester->name, $body);
                    $body = str_replace('[semester_label]', @$studentRecord->unSemesterLabel->name, $body);
                    if(moduleStatusCheck('Alumni')){
                        $alumni = Alumni::where('student_id',$student_id)->first();
                       
                         $alumni->certificate_id = $cer_id;
                         $alumni->save();
                        $body = str_replace('[graduation_date]', @dateConvert($alumni->graduation_date), $body);
                        $body = str_replace('[arabic_name]', @$alumni->ar_name, $body);
                        $body = str_replace('[name]', @$alumni->en_name, $body);
                       
                    }
                    $body = str_replace('[name]', @$student->full_name, $body);
                }
            }elseif($role==3){
                $parent = SmParent::where('user_id', $user_id)->first();
                $body = str_replace('[parent_name]', @$parent->guardians_name, $body);
                $body = str_replace('[parent_mobile]', @$parent->guardians_mobile, $body);
                $body = str_replace('[parent_email]', @$parent->guardians_email, $body);
                $body = str_replace('[parent_occupation]', @$parent->guardians_occupation, $body);
                $body = str_replace('[parent_address]', @$parent->guardians_address, $body);
                $body = str_replace('[profile_image]', @$parent->guardians_photo, $body);
            }elseif($role == "Lms"){
                if(moduleStatusCheck('Lms')== TRUE){
                    $purchaseLog = CoursePurchaseLog::where('student_id',$user_id)->first();
                    $body = str_replace('[student_name]', @$purchaseLog->student->full_name, $body);
                    $body = str_replace('[course_name]', @$purchaseLog->course->course_title, $body);
                    $complete_date = LessonComplete::where('course_id',$purchaseLog->course_id)->where('student_id',$purchaseLog->student_id)->latest()->first('created_at')->created_at;
                    if($complete_date){
                        $complete_date = dateConvert($complete_date);
                    }else{
                        $complete_date = null; 
                    }
                    $body = str_replace('[course_complete_date]', $complete_date, $body);
                }
            }else{
                $staff = SmStaff::where('user_id', $user_id)->first();
                $body = str_replace('[staff_name]', @$staff->full_name, $body);
                $body = str_replace('[date_of_birth]', @$staff->date_of_birth, $body);
                $body = str_replace('[present_address]', @$staff->current_address, $body);
                $body = str_replace('[date_of_joining]', @$staff->date_of_joining, $body);
                $body = str_replace('[email]', @$staff->email, $body);
                $body = str_replace('[mobile]', @$staff->mobile, $body);
                $body = str_replace('[qualification]', @$staff->qualification, $body);
                $body = str_replace('[experience]', @$staff->experience, $body);
                $body = str_replace('[profile_image]', @$staff->staff_photo, $body);
            }
            return $body;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        } 
        
    }

    public static function certificateNumber($certificate_no, $admission_no = null, $graduate_date = null){
        $value = "";
        if(in_array("admission_no", json_decode($certificate_no))){
            $value = $value.$admission_no;
        }
        if(in_array("graduate_date", json_decode($certificate_no))){
            $value = $value."/".$graduate_date;
        }
        return $value;
       

    }
}
