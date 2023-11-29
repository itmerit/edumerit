<?php

namespace App\Http\Requests\Admin\FrontSettings;

use App\SmCourseCategory;
use Illuminate\Foundation\Http\FormRequest;

class SmCourseCategoryRequest extends FormRequest
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
        $category = SmCourseCategory::find($this->id);
        $rules =  [
            'category_name' => 'required',
            'category_image' => 'required|dimensions:min_width=1420,min_height=450|max:'.$maxFileSize,
        ];

        if($category && $category->category_image){
            $rules['category_image'] = 'dimensions:min_width=1420,min_height=450|mimes:jpg,jpeg,png|max:'.$maxFileSize;
        }

        return $rules;
    }
}
