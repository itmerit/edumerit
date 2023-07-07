<?php

namespace App\Http\Requests\Admin\StudentInfo;

use Illuminate\Foundation\Http\FormRequest;

class StudentSubjectWiseAttendancSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        if (moduleStatusCheck('University')) {
            return [
                'attendance_date' => 'required|date',
                'un_session_id' => 'required',
                'un_faculty_id' => 'sometimes|nullable',
                'un_department_id' => 'required',
                'un_academic_id' => 'required',
                'un_semester_id' => 'required',
                'un_section_id' => 'required',
                'un_semester_label_id' => 'required',
                'un_subject_id' => 'required',
            ];
        } else {
            return [
                'class_id' => 'required | numeric ',
                'section_id' => 'required | numeric ',
                'subject_id' => 'required | numeric ',
                'attendance_date' => 'required|date',
            ];
        }
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
                    'un_subject_id' => "subject",
                ];
            }
        return $rules;
    }
    public function messages(): array
    {
        return [
            'class_id.required' => 'The class field is required.',
            'section_id.required' => 'The section field is required.',
            'subject_id.required' => 'The subject field is required.'
        ];
    }
}
