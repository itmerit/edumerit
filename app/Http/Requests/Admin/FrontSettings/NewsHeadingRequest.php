<?php

namespace App\Http\Requests\Admin\FrontSettings;

use Illuminate\Foundation\Http\FormRequest;

class NewsHeadingRequest extends FormRequest
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
            'main_title' => 'required',
            'main_description' => 'required',
            'button_text' => 'required',
            'button_url' => 'required',
            'image' => 'dimensions:min_width=1420,min_height=450|file|max:'.$maxFileSize,
            'main_image' => 'mimes:jpg,jpeg,png|dimensions:min_width=1420,min_height=450|file|max:'.$maxFileSize,
        ];
    }
}
