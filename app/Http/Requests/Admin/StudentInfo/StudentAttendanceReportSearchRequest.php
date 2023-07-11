<?php

namespace App\Http\Requests\Admin\StudentInfo;

use Illuminate\Foundation\Http\FormRequest;

class StudentAttendanceReportSearchRequest extends FormRequest
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
                'month' => 'required',
                'year' => 'required',
                'un_session_id' => 'required',
                'un_faculty_id' => 'required',
                'un_department_id' => 'sometimes|nullable',
                'un_academic_id' => 'required',
                'un_semester_id' => 'required',
                'un_semester_label_id' => 'required',
                
            ];
        } else {
            return [
                'class' => 'required',
                'section' => 'required',
                'month' => 'required',
                'year' => 'required'
            ];
        }
    }
}
