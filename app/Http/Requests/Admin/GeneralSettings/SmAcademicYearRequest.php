<?php

namespace App\Http\Requests\Admin\GeneralSettings;

use Illuminate\Foundation\Http\FormRequest;

class SmAcademicYearRequest extends FormRequest
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
            'year' => 'required|numeric|digits:4',
            'starting_date'=>'sometimes|nullable|date',
            'copy_with_academic_year'=>'sometimes|nullable|array',
            'starting_date' => 'required',
            'ending_date' => 'required',
            'title' => "required|max:150",
        ];
    }
}
