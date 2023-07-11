<?php

namespace App\Http\Requests\Admin\AdminSection;

use Illuminate\Foundation\Http\FormRequest;

class SmPhoneCallRequest extends FormRequest
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
            'phone' => "required|regex:/^([0-9\s\-\+\(\)]*)$/|",
            'name' => "sometimes|nullable|max:120",
            'call_duration' => "sometimes|nullable|max:30",
            'date' =>"sometimes|nullable|date",
            'follow_up_date' =>"sometimes|nullable|date|after:date",
            'description' =>"sometimes|nullable",
            'call_type' =>"required",
        ];
    }
}
