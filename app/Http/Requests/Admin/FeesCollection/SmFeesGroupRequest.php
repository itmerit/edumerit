<?php

namespace App\Http\Requests\Admin\FeesCollection;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SmFeesGroupRequest extends FormRequest
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
        $school_id=auth()->user()->school_id;
        return [
            'name' => ['required' ,'max:100', Rule::unique('sm_fees_groups')->where('school_id', $school_id)->where('academic_id', getAcademicId())->ignore($this->id) ],
            'description' =>"nullable|max:200",
        ];
    }
}
