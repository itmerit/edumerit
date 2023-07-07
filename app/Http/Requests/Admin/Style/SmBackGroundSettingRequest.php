<?php

namespace App\Http\Requests\Admin\Style;

use Illuminate\Foundation\Http\FormRequest;

class SmBackGroundSettingRequest extends FormRequest
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
        return [
            'background_type' => 'required',
            'style' => 'required',
            'color' => 'required_if:background_type,color',
            'image' => 'required_if:background_type,image|mimes:jpg,jpeg,png|max:'.$maxFileSize
        ];
    }
}
