<?php

namespace App\Http\Requests\Admin\Examination;

use Illuminate\Foundation\Http\FormRequest;

class SmExamSetupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'exams_types' => 'required',
            'exam_marks' => 'required|numeric|min:1',
            'subjects_ids' => 'required',
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
                'class_ids' => 'required',
            ];
        }
        return $rules;
    }

    public function attributes()
    {
        $rules = [
            'exams_types' =>"exam type",
            'subjects_ids' =>"subject",
        ];

        if (moduleStatusCheck('University')) {
            $rules += [
                'un_session_id' => "session",
                'un_faculty_id' => "faculty",
                'un_department_id' => "department",
                'un_academic_id' => "academic",
                'un_semester_id' => "semester",
                'un_semester_label_id' => "semester label",
                'un_subject_id' => "subject",
                'un_section_id' => "section",
            ];
        } else {
            $rules +=[
                'class_ids'=>"class",
            ];
        }
        return $rules;
    }
}
