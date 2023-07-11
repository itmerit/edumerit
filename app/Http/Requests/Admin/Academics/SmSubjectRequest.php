<?php

namespace App\Http\Requests\Admin\Academics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SmSubjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $rules = [
            'subject_name' => ['required', 'max:200' , Rule::unique('sm_subjects', 'subject_name')->where('academic_id', getAcademicId())->where('school_id', auth()->user()->school_id)->ignore($this->id)],
            'subject_type' => "required",
            'subject_code' => ['sometimes', 'required', 'max:200' , Rule::unique('sm_subjects', 'subject_code')->where('academic_id', getAcademicId())->where('school_id', auth()->user()->school_id)->ignore($this->id)],
        ];

        if (@generalSetting()->result_type == 'mark') {
            $rules += [
                'pass_mark' => 'required',
            ];
        }
        return $rules;
    }
}
