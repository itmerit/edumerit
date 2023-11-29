<?php

namespace App\Http\Requests\Admin\StudentInfo;

use Illuminate\Foundation\Http\FormRequest;

class SmStudentAttendanceSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (moduleStatusCheck('University')) {
            return [
                'attendance_date' => 'required|date',
                'un_session_id' => 'sometimes|nullable',
                'un_faculty_id' => 'sometimes|nullable',
                'un_department_id' => 'sometimes|nullable',
                'un_academic_id' => 'sometimes|nullable',
                'un_semester_id' => 'sometimes|nullable',
                'un_semester_label_id' => 'sometimes|nullable',
                'un_subject_id' => 'sometimes|nullable',
                'un_section_id' => 'required',
            ];
        } else {
            return [
                'class_id' => 'required|integer',
                'section_id'=>'required|integer',
                'attendance_date' => 'required|date'
            ];
        }
    }
}
