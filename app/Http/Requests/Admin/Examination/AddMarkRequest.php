<?php

namespace App\Http\Requests\Admin\Examination;

use Illuminate\Foundation\Http\FormRequest;

class AddMarkRequest extends FormRequest
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
                'exam_type' => 'required',
                'subject_id' => 'required',
                'un_section_id' => 'required',
            ];
        } else {
            $rules +=[
                'exam' => 'required',
                'class' => 'required',
                'section' => 'nullable',
                'subject' => 'required'
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
                'subject_id' => "subject",
                'un_section_id' => "section",
            ];
        }
        return $rules;
    }
}
