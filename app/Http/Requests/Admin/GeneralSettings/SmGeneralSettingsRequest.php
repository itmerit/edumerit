<?php

namespace App\Http\Requests\Admin\GeneralSettings;

use Illuminate\Foundation\Http\FormRequest;

class SmGeneralSettingsRequest extends FormRequest
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
            'school_name' => "required",
            'site_title' => "required",
            'session_id' => "required",
            'school_code' => "required",
            'phone' => "required",
            'email' => "required",
            'income_head' => "required",
            'language_id' => "required",
            'week_start_id' => "required",
            'date_format_id' => "required",
            'time_zone' => "required",
            'currency' => "required",
            'currency_symbol' => "required", 
            'promotionSetting' => "required",
            'ss_page_load' => "required",          
            'attendance_layout' => "required",
            'address' => "required",
        ];
    }
}
