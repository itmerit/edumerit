<?php

namespace App\Http\Requests\Admin\Reports;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FinalMarkSheetRequest extends FormRequest
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
        if(moduleStatusCheck('University')){
            return [
                'un_session_id' => "required",
                'un_faculty_id' => "required",
                'un_department_id' => "required",
                'un_academic_id' => "required",
                'un_semester_id' => "required",
                'un_semester_label_id' => "required",
                'un_section_id' => "required",
            ];
        }else{
            return [
                'class' => 'required',
                'section'=> 'required'
            ];
        }
    }
}
