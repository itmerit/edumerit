<?php

namespace App\Http\Requests\Admin\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class SmBankAccountRequest extends FormRequest
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
            'bank_name' => "required",
            'account_name' => "required",
            'account_number' => "required|unique:sm_bank_accounts,account_number",
            'account_type' => "sometimes|nullable",
            'opening_balance' => "required|numeric",           
            'note'=>"sometimes|nullable|max:200",
        ];
    }
}
