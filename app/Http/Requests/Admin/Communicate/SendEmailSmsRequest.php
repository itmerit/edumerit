<?php

namespace App\Http\Requests\Admin\Communicate;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailSmsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        $rules = [
            'email_sms_title' => "required",
            'send_through' => "required",
            'description' => "required",
            'role'=>"required_if:selectTab,G|array",
            'role_id'=>"required_if:selectTab,I",
            'message_to_individual'=>"required_with:role_id|array",
        ];

        if (moduleStatusCheck('University')) {
            if($this->selectTab == "C"){
                $rules += [
                    'un_session_id' => 'required',
                    'un_faculty_id' => 'nullable',
                    'un_department_id' => 'required',
                    'un_academic_id' => 'required',
                    'un_semester_id' => 'required',
                    'un_semester_label_id' => 'required',
                    'un_section_id' => 'nullable',
                ];
            }else{
                $rules += [];
            }
        } else {
            $rules +=[
                'class_id'=>"required_without:selectTab",
                'message_to_section'=>"required_with:class_id|array",
                'selectTab'=>'sometimes|nullable'
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
        } else {
            $rules +=[
                'class_ids'=>"class",
            ];
        }
        return $rules;
    }
}
