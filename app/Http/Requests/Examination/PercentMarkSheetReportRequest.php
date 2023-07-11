<?php

namespace App\Http\Requests\Examination;

use Illuminate\Foundation\Http\FormRequest;

class PercentMarkSheetReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if(moduleStatusCheck('University')){
            return [
                'exam_type' => "required",
                'un_session_id' => "required",
                'un_faculty_id' => "required",
                'un_department_id' => "required",
                'un_academic_id' => "required",
                'un_semester_id' => "required",
                'un_semester_label_id' => "required",
            ];
        }else{
            return [
                'exam_type' => "required",
                'subject' => "required",
                'class' => "required",
                'section' => "required",
            ];
        }
    }
}
