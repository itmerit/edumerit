<?php

namespace App\Http\Requests\Admin\FrontSettings;

use Illuminate\Foundation\Http\FormRequest;

class SmContactUsRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'button_text' => 'required',
            'button_url' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'zoom_level' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'google_map_address' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png|dimensions:min_width=1420,min_height=450
            |max:'.$maxFileSize,
        ];
    }
}
