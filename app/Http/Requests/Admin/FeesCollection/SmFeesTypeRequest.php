<?php

namespace App\Http\Requests\Admin\FeesCollection;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SmFeesTypeRequest extends FormRequest
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
            'name' => ['required', 'max:50', Rule::unique('sm_fees_types')->where('school_id', $school_id)->where('fees_group_id', $this->fees_group)->ignore($this->id) ],
            'fees_group' => "required|integer",
            'description' => "nullable|max:200",
        ];
    }
}
