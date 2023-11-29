<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;
    protected $table = "sms_templates";

    public static function smsTempleteToBody($body, $data)
    {
        $user = null;
        if (@$data['user_email']) {
            $user = User::where('email', $data['user_email'])->first();
        }

        if($user->role_id == 2){
            $body = str_replace('[student_name]', gv($data, 'student_name', @$user->full_name), $body);
            $body = str_replace('[name]', @$user->full_name, $body);
            $body = str_replace('[password]', '123456', $body);
        }elseif($user->role_id == 3){
            $body = str_replace('[student_name]', @$data['student_name'], $body);
            $body = str_replace('[parent_name]',gv($data, 'parent_name', @$user->full_name), $body);
            $body = str_replace('[password]', '123456', $body);
        }else{
            $body = str_replace('[name]', @$user->full_name, $body);
            $body = str_replace('[attendance_date]', @$data['attendance_date'], $body);
            $body = str_replace('[password]', '123456', $body);
        }

        $body = str_replace('[name]', @$data['name'], $body);
        
        if (@$data['slug'] == 'student') {
            $student_info = SmStudent::find(@$data['id']);
            $body = str_replace('[student_name]', gv($data, 'student_name', @$student_info->full_name), $body);
            $body = str_replace('[user_name]', @$user->username . '/' . @$user->email, $body);
        } elseif (@$data['slug'] == 'parent') {
            $parent_info = SmParent::find(@$data['id']);
            $student_info = SmStudent::where('parent_id', @$parent_info->id)->first();
            $body = str_replace('[parent_name]', gv($data, 'parent_name', @$parent_info->guardians_name), $body);
            $body = str_replace('[student_name]', gv($data, 'student_name', @$student_info->full_name), $body);
            $body = str_replace('[user_name]', @$user->username, $body);
        } else {
            $body = str_replace('[user_name]', @$user->email, $body);
            $body = str_replace('[staff_name]', gv($data, 'staff_name', @$user->full_name), $body);
            $body = str_replace('[password]', '123456', $body);
        }

        $body = str_replace('[title]', @$data['title'], $body);
        $body = str_replace('[description]', @$data['description'], $body);


        $body = str_replace('[class_name]', @$data['class_name'], $body);
        $body = str_replace('[section_name]', @$data['section_name'], $body);
        $body = str_replace('[exam_type]', @$data['exam_type'], $body);
        $body = str_replace('[subject_marks]', @$data['subject_marks'], $body);

        $body = str_replace('[school_name]', gv($data, 'school_name', @generalSetting()->school_name), $body);

        $body = str_replace('[attendance_date]', @$data['attendance_date'], $body);
        
        $body = str_replace('[exam_date]', @$data['exam_date'], $body);
        $body = str_replace('[exam_time]', @$data['exam_time'], $body);

        $body = str_replace('[due_date]', @$data['due_date'], $body);
        $body = str_replace('[roll_no]', @$data['roll_no'], $body);
        $body = str_replace('[issue_date]', @$data['issue_date'], $body);
        $body = str_replace('[book_title]', @$data['book_title'], $body);
        $body = str_replace('[book_no]', @$data['book_no'], $body);
        $body = str_replace('[return_date]', @$data['return_date'], $body);

        $body = str_replace('[dues_amount]', @$data['due_amount'], $body);
        $body = str_replace('[fees_name]', @$data['fees_name'], $body);
        $body = str_replace('[date]', @$data['date'], $body);

        $body = str_replace('[holiday_date]', @$data['holiday_date'], $body);

        $body = str_replace('[number_of_subject]', @$data['number_of_subject'], $body);
        $body = str_replace('[subject_list]', @$data['subject_list'], $body);
        
        $body = str_replace('[student_name]', @$data['student_name'], $body);
        $body = str_replace('[parent_name]', @$data['parent_name'], $body);

        //two_factor_authenticate
        $body = str_replace('[first_name]', @$data['first_name'], $body);
        $body = str_replace('[last_name]', @$data['last_name'], $body);
        $body = str_replace('[full_name]', @$data['full_name'], $body);
        $body = str_replace('[code]', @$data['code'], $body);
        $body = str_replace('[expired_time]', @$data['expired_time'], $body);


        return $body;
    }

    public static function emailTempleteToBody($body, $data)
    {
        
       $user = null;
        if (@$data['user_email']) {
            $user = User::where('email', $data['user_email'])->first();
            $school = SmSchool::find($user->school_id);

            $body = str_replace('[name]', @$user->full_name, $body);
            $body = str_replace('[email]', @$user->email, $body);
            $body = str_replace('[school_name]', @generalSetting()->school_name, $body);
        }

        $body = str_replace('[user_name]', @$data['user_name'], $body);

        //Global Variable End

        //Password Reset Start
        $body = str_replace('[admission_number]', @$data['admission_number'], $body);
        $reset_link = url('reset/password' . '/' . @$data['user_email'] . '/' . @$data['random']);

        $body = str_replace('http://[reset_link]', @$reset_link, $body);
        $body = str_replace('https://[reset_link]', @$reset_link, $body);
        $body = str_replace('//[reset_link]', @$reset_link, $body);
        $body = str_replace('[reset_link]', @$reset_link, $body);
        
        //Password Reset End

        // FrontEnd Contact Start
        $body = str_replace('[contact_name]', @$data['contact_name'], $body);
        $body = str_replace('[contact_email]', @$data['contact_email'], $body);
        $body = str_replace('[contact_subject]', @$data['subject'], $body);
        $body = str_replace('[contact_message]', @$data['contact_message'], $body);
        // FrontEnd Contact End

        // Login Information Start
        $body = str_replace('[password]', gv($data, 'password', '123456'), $body);
        $body = str_replace('[institute_name]', gv($data, 'institute_name'), $body);
        $body = str_replace('[application_name]', gv($data, 'applicateion_name'), $body);
        $body = str_replace('[login_url]', gv($data, 'login_url'), $body);
        $body = str_replace('[name]', gv($data, 'name'), $body);


        $body = str_replace('[title]', @$data['title'], $body);
        $body = str_replace('[description]', @$data['description'], $body);

        if (@$data['slug'] == 'student') {
            $student_info = SmStudent::find(@$data['id']);
            $parent_info = SmParent::find(@$student_info->parent_id);

            $body = str_replace('[student_name]', @$student_info->full_name, $body);
            $body = str_replace('[father_name]', gv($data, 'father_name', @$parent_info->fathers_name), $body);
            $body = str_replace('[username]', @$user->username . '/' . @$user->email, $body);
            $body = str_replace('[admission_number]', @$student_info->admission_no, $body);
        } elseif (@$data['slug'] == 'parent') {
            $parent_info = SmParent::find(@$data['id']);
            $student_info = SmStudent::where('parent_id', @$parent_info->id)->first();

            $body = str_replace('[name]', @$parent_info->guardians_name, $body);
            $body = str_replace('[parent_name]', @$parent_info->guardians_name, $body);
            $body = str_replace('[student_name]', @$student_info->full_name, $body);
            $body = str_replace('[username]', @$user->username, $body);
            $body = str_replace('[email]', @$user->email, $body);
            $body = str_replace('[father_name]', gv($data, 'father_name', @$student_info->father_name), $body);
            $body = str_replace('[admission_number]', @$student_info->admission_no, $body);
        } else {
            $body = str_replace('[username]', gv($data, 'username', @$user->username), $body);
            $body = str_replace('[email]', gv($data, 'email', @$user->email), $body);
        }
        // Login Information End

        //Bank Reject Payment Start
        $body = str_replace('[student_name]', @$data['student_name'], $body);
        $body = str_replace('[father_name]', @$data['father_name'], $body);
        $body = str_replace('[parent_name]', @$data['parent_name'], $body);
        $body = str_replace('[note]', @$data['note'], $body);
        $body = str_replace('[date]', @$data['date'], $body);
        //Bank Reject Payment End

        //lead module 
        $body = str_replace('[assign_user]', @$data['lead_assign_user'], $body);
        $body = str_replace('[lead_name]', @$data['lead_name'], $body);
        $body = str_replace('[lead_email]', @$data['lead_email'], $body);
        $body = str_replace('[lead_phone]', @$data['lead_phone'], $body);
        $body = str_replace('[reminder_date]', @$data['reminder_date'], $body);
        $body = str_replace('[reminder_time]', @$data['reminder_time'], $body);
        $body = str_replace('[reminder_description]', @$data['reminder_description'], $body);
        //end module

        // Wallet Start
        $body = str_replace('[add_balance]',  currency_format(@$data['add_balance']), $body);
        $body = str_replace('[method]', @$data['method'], $body);
        $body = str_replace('[create_date]', dateConvert(@$data['create_date']), $body);
        $body = str_replace('[current_balance]', currency_format(@$data['current_balance']), $body);
        $body = str_replace('[reject_reason]', @$data['reject_reason'], $body);
        $body = str_replace('[previous_balance]', @$data['previous_balance'], $body);
        $body = str_replace('[refund_amount]', currency_format(@$data['refund_amount']), $body);
        
        // Wallet End

        $body = str_replace('[class_name]', @$data['class_name'], $body);
        $body = str_replace('[section_name]', @$data['section_name'], $body);
        $body = str_replace('[exam_type]', @$data['exam_type'], $body);
        $body = str_replace('[subject_marks]', @$data['subject_marks'], $body);
        
        $body = str_replace('[parent_name]', @$data['parent_name'], $body);

        $body = str_replace('[due_amount]', @$data['due_amount'], $body);
        $body = str_replace('[fees_name]', @$data['fees_name'], $body);

        $body = str_replace('[school_name]', @generalSetting()->school_name, $body);

        //two_factor_authenticate
        $body = str_replace('[first_name]', @$data['first_name'], $body);
        $body = str_replace('[last_name]', @$data['last_name'], $body);
        $body = str_replace('[full_name]', @$data['full_name'], $body);
        $body = str_replace('[code]', @$data['code'], $body);
        $body = str_replace('[expired_time]', @$data['expired_time'], $body);

        return $body;
    }
}
