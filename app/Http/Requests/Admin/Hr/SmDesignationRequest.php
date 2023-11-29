<?php

namespace App\Http\Requests\Admin\Hr;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SmDesignationRequest extends FormRequest
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
            'title' => ['required', 'max:200', Rule::unique('sm_designations')->where('school_id', $school_id)->ignore($this->id)],
        ];
    }
}
