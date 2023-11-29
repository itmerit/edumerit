<?php

namespace App\Http\Requests\Admin\GeneralSettings;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SmCurrencyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $school_id=auth()->user()->school_id;
        return [
            'name' => ['required', 'max:25'],
            'code' => ['required', 'max:15' ,Rule::unique('sm_currencies', 'code')->where('school_id', $school_id)->ignore($this->id) ],
            'symbol' => 'required | max:15',
            'currency_type'=>['required', 'in:S,C'],
            'currency_position'=>['required', 'in:S,P'],
            'space'=>['required'],
            'decimal_digit'=>['sometimes', 'nullable', 'max:5'],
            'decimal_separator'=>['sometimes', 'nullable', 'max:1'],
            'thousand_separator'=>['sometimes', 'nullable', 'max:1'],
        ];
    }
}
