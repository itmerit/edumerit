<?php

namespace App\Http\Requests\Admin\Examination;

use Illuminate\Foundation\Http\FormRequest;

class SmExamScheduleSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'exam_type' => 'required',
        ];

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
                'class' => 'required',
                'section' => 'sometimes|nullable',
            ];
        }
        return $rules;
    }

    public function attributes()
    {
        $rules = ['exam_type'=>"exam"];

        if (moduleStatusCheck('University')) {
            $rules += [
                'un_session_id' => "session",
                'un_faculty_id' => "faculty",
                'un_department_id' => "department",
                'un_academic_id' => "academic",
                'un_semester_id' => "semester",
                'un_semester_label_id' => "semester label",
                'un_section_id' => "Section",
            ];
        }
        return $rules;
    }
}
