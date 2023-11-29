<?php

namespace App\Http\Requests\Admin\AdminSection;

use Illuminate\Foundation\Http\FormRequest;

class SmStudentIdCardRequest extends FormRequest
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
        $maxFileSize =generalSetting()->file_size*1024;
        $rules = [
            'title' => 'required',
            'page_layout_style' => 'required',
            'applicable_user.*' => 'required',
            'role' => 'required_if:applicable_user,0',
            'user_photo_style' => 'nullable',
            'user_photo_width' =>'nullable',
            'user_photo_height' =>'nullable',
            'pl_width' =>'nullable',
            'pl_height' =>'nullable',
            't_space' =>'nullable',
            'b_space' =>'nullable',
            'l_space' =>'nullable',
            'r_space' =>'nullable',
            'admission_no' =>'nullable',
            'student_name' =>'nullable',
            'class' =>'nullable',
            'father_name' =>'nullable',
            'mother_name' =>'nullable',
            'student_address' =>'nullable',
            'dob'=>'nullable',
            'blood' =>'nullable',
            'phone_number'=>'nullable',
        ];
        if ($this->id) {         
            $rules['logo'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:'.$maxFileSize;
            $rules['signature'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:'.$maxFileSize;
        } else {
            $rules['logo'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:'.$maxFileSize;
            $rules['signature'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:'.$maxFileSize;
        }

        return $rules;
    }
}
