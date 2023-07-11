<?php

namespace Modules\Fees\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'create_date' => 'required',
            'due_date' => 'required',
            'payment_status' => 'required',
            'payment_method' => 'required_if:payment_status,partial|required_if:payment_status,full',
            'bank' => 'required_if:payment_method,Bank',
            // 'fees_type' => 'required',
        ];

        if (moduleStatusCheck('University')) {
            $rules += [
                'student_id' => 'required',
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
                'student' => 'required',
            ];
        }
        return $rules;
    }

    public function attributes()
    {
        $rules = [];
        
        if (moduleStatusCheck('University')) {
            $rules = [
                'un_session_id' => "session",
                'un_faculty_id' => "faculty",
                'un_department_id' => "department",
                'un_academic_id' => "academic",
                'un_semester_id' => "semester",
                'un_semester_label_id' => "semester label",
                'un_section_id' => "section",
                'student_id' => "student",
            ];
        }else{
            $rules = [];
        }
        
        return $rules;
    }

    public function authorize()
    {
        return true;
    }
}
