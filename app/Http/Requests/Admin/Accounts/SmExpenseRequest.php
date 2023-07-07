<?php

namespace App\Http\Requests\Admin\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class SmExpenseRequest extends FormRequest
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
        $maxFileSize = generalSetting()->file_size*1024;
        return [
            'expense_head' => "required",
            'name' => "required",
            'date' => "required|date",
            'payment_method' => "required",
            'accounts' => "required_if:payment_method,Bank",           
            'amount' => "required",
            'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt|max:".$maxFileSize,
            'description' =>"sometimes|nullable",
        ];
    }
}
