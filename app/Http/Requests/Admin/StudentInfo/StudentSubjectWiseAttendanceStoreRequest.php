<?php

namespace App\Http\Requests\Admin\StudentInfo;

use Illuminate\Foundation\Http\FormRequest;

class StudentSubjectWiseAttendanceStoreRequest extends FormRequest
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
                'un_subject_id' => 'required',
            ];
        } else {
            $rules +=[
                'class' => 'required | numeric ',
                'section' => 'required | numeric ',
                'subject' => 'required | numeric ',
                'date' => 'required|date',
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
                    'un_subject_id' => "subject",
                ];
            }
            return $rules;
        }
}
