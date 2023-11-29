<?php

namespace App\Http\Requests\Admin\GeneralSettings;

use Illuminate\Foundation\Http\FormRequest;

class SmEmailSettingsRequest extends FormRequest
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
            'from_name' => "required_if:engine_type,php",
            'from_email' => "required_if:engine_type,php|email",

            'from_name' => "required_if:engine_type,smtp",
            'from_email' => "required_if:engine_type,smtp|email",
            'mail_password' => "required_if:engine_type,smtp",
            'mail_encryption' => "required_if:engine_type,smtp",

        ];
    }
}
