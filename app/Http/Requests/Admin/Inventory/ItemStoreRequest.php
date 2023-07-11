<?php

namespace App\Http\Requests\Admin\Inventory;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
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
            'store_name' => ['required', Rule::unique('sm_item_stores', 'store_name')->where('school_id', auth()->user()->school_id)->ignore($this->id)],
            'store_no' => "required",
            'description' =>'sometimes|nullable',
        ];
    }
}
