<?php

namespace App\Http\Requests\Admin\AdminSection;

use Illuminate\Foundation\Http\FormRequest;

class GenerateCertificateSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $maxFileSize=generalSetting()->file_size*1024;
        $rules = [
            'certificate'=>'required'
        ];
        if (moduleStatusCheck('University')){
            $rules += [
                'un_department_id' => ['required'],
                'un_faculty_id' => ['required'],
                'un_session_id' => ['required']
            ];
        } else {
            $rules += [
                'class' => ['required'],
            ];
        }
        return $rules;
    }
}
