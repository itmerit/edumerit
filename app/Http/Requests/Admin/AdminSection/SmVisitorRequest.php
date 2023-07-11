<?php

namespace App\Http\Requests\Admin\AdminSection;

use Illuminate\Foundation\Http\FormRequest;

class SmVisitorRequest extends FormRequest
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
            
            'purpose' => "required|max:250",
            'name' => "required|max:120",
            'phone' => ['nullable', 'min:4'],
            'visitor_id' => "required|max:15",
            'no_of_person' => "required|max:10",
            'date' => "required",
            'in_time' => "required",
            'out_time' => "required|after:in_time",
            'upload_event_image' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt|max:".$maxFileSize,
        ];
    }
}
