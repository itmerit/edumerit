<?php

namespace App\Http\Requests\Admin\Communicate;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
        $maxFileSize=generalSetting()->file_size*1024;
        
        $rules = [
            'event_title' => "required",
            'from_date' => "required|date",
            'to_date' => "required|date|after_or_equal:from_date",
            'event_des' => "required",
            'event_location' => 'required',
            'upload_event_image' => "mimes:jpg,jpeg,png,gif|max:".$maxFileSize,
        ];

        if (!$this->id) {
            $rules['for_whom'] = "required";
            $rules['upload_event_image'] = "mimes:jpg,jpeg,png,gif|max:".$maxFileSize;
        }

        return $rules;
    }
}
