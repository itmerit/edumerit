<?php

namespace App\Http\Requests\Admin\Examination;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SmMarkGradeRequest extends FormRequest
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
        
        if(generalSetting()->result_type != 'mark'){
            return [
                'grade_name' => ['required', 'max:50' , Rule::unique('sm_marks_grades')->where('school_id', $school_id)->ignore($this->id) ],
                'gpa' => ['required', Rule::unique('sm_marks_grades')->where('school_id', $school_id)->ignore($this->id) ],
                'percent_from' => "required|min:0",
                'percent_upto' => "required|gt:percent_from|min:",
                'grade_from' => "required|min:0",
                'grade_upto' => "required|gt:grade_from|min:",
                'description'=>'sometimes|nullable'
            ];
        }else{
            return [
                'grade_name' => ['required', 'max:50' , Rule::unique('sm_marks_grades')->where('school_id', $school_id)->ignore($this->id) ],
                'percent_from' => "required|min:0",
                'percent_upto' => "required|gt:percent_from|min:0",
                'description'=>'required'
            ];
        }

    }
}
