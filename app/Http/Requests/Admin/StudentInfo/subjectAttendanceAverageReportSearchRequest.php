<?php

namespace App\Http\Requests\Admin\StudentInfo;

use Illuminate\Foundation\Http\FormRequest;

class subjectAttendanceAverageReportSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        if (moduleStatusCheck('University')) {
            $rules += [
                'un_session_id' => 'required',
                'un_faculty_id' => 'nullable',
                'un_department_id' => 'required',
                'un_academic_id' => 'required',
                'un_semester_id' => 'required',
                'un_semester_label_id' => 'required',
                'un_section_id' => 'required',
            ];
        } else {
            $rules +=[
                'class_id' => 'required',
                'section_id' => 'required',
                'month' => 'required',
                'year' => 'required'
            ];
        }
        return $rules;
    }

    public function attributes()
    {
        $rules = [];
        if (moduleStatusCheck('University')) {
            $rules += [
                'un_session_id' => "session",
                'un_faculty_id' => "faculty",
                'un_department_id' => "department",
                'un_academic_id' => "academic",
                'un_semester_id' => "semester",
                'un_semester_label_id' => "semester label",
                'un_section_id' => "section",
            ];
        }else {
            $rules += [
                'class_id'=>'class',
                'section_id'=>'section',
            ];
        }
        return $rules;
    }
}
