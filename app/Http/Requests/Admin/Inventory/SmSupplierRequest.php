<?php

namespace App\Http\Requests\Admin\Inventory;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SmSupplierRequest extends FormRequest
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
            'company_name' => "required|max:200",
            'company_address' => "required",
            'contact_person_name' => "required|max:200",
            'contact_person_email' => ['required', 'max:200' , Rule::unique('sm_suppliers', 'contact_person_email')->where('school_id', auth()->user()->school_id)->ignore($this->id)],
            'contact_person_mobile' => ['required', 'max:200' , Rule::unique('sm_suppliers', 'contact_person_mobile')->where('school_id', auth()->user()->school_id)->ignore($this->id)],
            'description' => "sometimes|nullable"
        ];
    }
}
