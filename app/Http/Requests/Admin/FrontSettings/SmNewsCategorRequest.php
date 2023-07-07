<?php

namespace App\Http\Requests\Admin\FrontSettings;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SmNewsCategorRequest extends FormRequest
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
            'category_name' => ['required', 'max:200' , Rule::unique('sm_news_categories', 'category_name')->where('school_id', auth()->user()->school_id)->ignore($this->id)],
        ];
       

    }
}
