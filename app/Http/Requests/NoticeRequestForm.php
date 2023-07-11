<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoticeRequestForm extends FormRequest
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
            'notice_title'=>['required'],
            'notice_date'=>['required', 'date'],
            'role' => ['required', 'array'],
            'publish_on'=>['required', 'after_or_equal:notice_date', 'date']
        ];
    }
}
