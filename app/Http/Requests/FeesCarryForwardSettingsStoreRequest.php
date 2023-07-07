<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeesCarryForwardSettingsStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'fees_due_days' => 'required|integer',
            'payment_gateway' => 'required|string',
        ];
    }
}
