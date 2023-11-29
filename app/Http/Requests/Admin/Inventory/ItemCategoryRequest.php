<?php

namespace App\Http\Requests\Admin\Inventory;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ItemCategoryRequest extends FormRequest
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
        $school_id=auth()->user()->school_id;
        return [
            'category_name'=>['required', Rule::unique('sm_item_categories')->where('school_id', $school_id)->ignore($this->id) ],
        ];
    }
}
