<?php

namespace Modules\Fees\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAddFeesPaymentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'payment_method' =>  'required',
            'bank' =>  'required_if:payment_method,Bank',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
