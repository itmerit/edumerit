<?php

namespace App\Http\Requests\Admin\Hr;

use Illuminate\Foundation\Http\FormRequest;

class staffAttendanceBulkStoreRequest extends FormRequest
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
            'attendance_date' => 'required|date',
            'file' => 'required|mimes:xlsx, csv'
        ];
    }
}
