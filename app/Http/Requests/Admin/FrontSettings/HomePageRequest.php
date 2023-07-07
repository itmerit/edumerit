<?php

namespace App\Http\Requests\Admin\FrontSettings;


use Illuminate\Foundation\Http\FormRequest;

class HomePageRequest extends FormRequest
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
            'long_title' => 'required',
            'short_description' => 'required',
            'image' => "sometimes|nullable|mimes:jpg,jpeg,png|file|max:".$maxFileSize,
        ];
    }
}
