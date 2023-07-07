<?php

namespace App\Http\Requests\Admin\Style;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ThemeFormRequest extends FormRequest
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
           'title'=>['required', 'max:30',Rule::unique('themes', 'title')->where('school_id', auth()->user()->school_id)->ignore($this->theme_id)],
           'background_type'=>['required', 'in:image,color'],
           'background_image'=>['required_if:background_type,image'],
           'background_color'=>['required_if:background_type,color'],
        ];
    }
}
