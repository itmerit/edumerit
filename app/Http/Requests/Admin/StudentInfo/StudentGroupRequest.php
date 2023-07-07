<?php

namespace App\Http\Requests\Admin\StudentInfo;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StudentGroupRequest extends FormRequest
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
        return [
            'group' => ['required', Rule::unique('sm_student_groups')->where('school_id', auth()->user()->school_id)->ignore($this->id) ],
        ];
    }
}
