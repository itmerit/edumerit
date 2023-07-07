<?php

namespace App\Http\Requests\Admin\AdminSection;

use Illuminate\Foundation\Http\FormRequest;

class SmComplaintRequest extends FormRequest
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
        $maxFileSize =generalSetting()->file_size*1024;
        return [
            'complaint_by' => "required|max:250",
            'complaint_type' => "required",
            'complaint_source' => "required",
            'phone'=>"nullable",
            'description'=>"nullable",
            'assigned'=>"nullable",
            'action_taken'=>"nullable",
            'date' => "required|date",
            'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt|max:".$maxFileSize,
        ];
    }
}
