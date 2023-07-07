<?php

namespace App\Http\Requests\Admin\FrontSettings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SmPageRequest extends FormRequest
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
            'slug' => ['required', Rule::unique('sm_pages', 'slug')->where('school_id', auth()->user()->school_id)->ignore($this->id)],
            'details' => 'required',
            'sub_title'=>'nullable',
            'header_image' => 'nullable|dimensions:min_width=1420,min_height=450|max:'.$maxFileSize,
        ];
    }
}
