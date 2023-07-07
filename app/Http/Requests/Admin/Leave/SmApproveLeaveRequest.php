<?php

namespace App\Http\Requests\Admin\Leave;

use Illuminate\Foundation\Http\FormRequest;

class SmApproveLeaveRequest extends FormRequest
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
        $maxFileSize=generalSetting()->file_size*1024;
        return [
            'staff_id' => "required",
            'apply_date' => "required",
            'leave_type' => "required",
            'leave_from' => "required",
            'leave_to' => "required",
            'reason' => "required",
            'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt|max:".$maxFileSize,
        ];
    }
}
