<?php

namespace App\Http\Requests\Admin\Hr;

use App\Models\SmStaffRegistrationField;
use App\SmStaff;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class staffRequest extends FormRequest
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
        $staff = null;
        $id = $this->id;
        if (!$id) {
            $id = $this->staff_id;
        }
        $school_id = auth()->user()->school_id;
        if ($id) {
            $staff = SmStaff::withOutGlobalScopes()->where('school_id', $school_id)->findOrFail($id);
        }

        $academic_id = getAcademicId();

        $field = SmStaffRegistrationField::where('school_id', $school_id);
        if($staff && auth()->user()->staff->id == $staff->id){
            $field = $field->where('staff_edit', 1)->where('is_required', 1);
        } else{
            $field = $field->where('is_required', 1);
        }

        $field = $field->pluck('field_name')
            ->toArray();
      
        $school_id=auth()->user()->school_id;

        return        
            [
                'staff_no' => [Rule::requiredIf(function () use ($field) {
                    return in_array('staff_no', $field);
                }), 'integer', Rule::unique('sm_staffs', 'staff_no')->where('school_id', $school_id)->ignore($id) ],
                'role_id' => [Rule::requiredIf(function () use ($field) {
                    return in_array('role_id', $field);
                })],
                'staff_photo' => [Rule::requiredIf(function () use ($field) {
                    return in_array('staff_photo', $field);
                }),'image','mimes:jpeg,png,jpg','max:'.$maxFileSize],
                'department_id' => [Rule::requiredIf(function () use ($field) {
                    return in_array('department_id', $field);
                })],
                'designation_id' => [Rule::requiredIf(function () use ($field) {
                    return in_array('designation_id', $field);
                })],
                'first_name' => [Rule::requiredIf(function () use ($field) {
                    return in_array('first_name', $field);
                }),'max:200'],
                'last_name' => [Rule::requiredIf(function () use ($field) {
                    return in_array('last_name', $field);
                }),'max:200'],
                'fathers_name'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('fathers_name', $field);
                }),'max:200'],
                'mothers_name'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('mothers_name', $field);
                }),'max:200'],
                'email' => [Rule::requiredIf(function () use ($field) {
                    return in_array('email', $field);
                }),Rule::unique('users', 'email')->ignore($staff ? $staff->user_id : null)],
                'gender_id' => [Rule::requiredIf(function () use ($field) {
                    return in_array('gender_id', $field);
                })],
                'date_of_birth' => [Rule::requiredIf(function () use ($field) {
                    return in_array('date_of_birth', $field);
                }),'nullable','date'],
                'date_of_joining' => [Rule::requiredIf(function () use ($field) {
                    return in_array('date_of_joining', $field);
                }),'date'],
                'mobile' => [Rule::requiredIf(function () use ($field) {
                    return in_array('mobile', $field);
                }),'nullable','numeric','min:11',Rule::unique('users', 'phone_number')->ignore($staff ? $staff->user_id : null)],
                'marital_status' => [Rule::requiredIf(function () use ($field) {
                    return in_array('marital_status', $field);
                })],
                'emergency_mobile'=> [Rule::requiredIf(function () use ($field) {
                    return in_array('emergency_mobile', $field);
                })],
                'driving_license' => [Rule::requiredIf(function () use ($field) {
                    return in_array('driving_license', $field);
                })],
                'staff_photo' => ['image','mimes:jpeg,png,jpg','max:'.$maxFileSize],
                'current_address' => [Rule::requiredIf(function () use ($field) {
                    return in_array('current_address', $field);
                }),'max:255'],
                'permanent_address' => [Rule::requiredIf(function () use ($field) {
                    return in_array('permanent_address', $field);
                }),'max:255'],
                'qualification'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('qualification', $field);
                }),'max:255'],
                'experience'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('experience', $field);
                }),'max:255'],
                'epf_no' =>[Rule::requiredIf(function () use ($field) {
                    return in_array('epf_no', $field);
                }),'max:255'],
                'basic_salary' => [Rule::requiredIf(function () use ($field) {
                    return in_array('basic_salary', $field);
                }),'max:100'],
                'basic_salary'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('basic_salary', $field);
                })],
                'contract_type'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('contract_type', $field);
                })],
                'location'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('location', $field);
                }),'max:255'],
                'bank_account_name'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('bank_account_name', $field);
                }),'max:255'],
                'bank_account_no'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('bank_account_no', $field);
                }),'max:255'],
                'bank_brach'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('bank_brach', $field);
                }),'max:255'],
                'facebook_url'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('facebook_url', $field);
                })],
                'twiteer_url'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('twiteer_url', $field);
                })],
                'linkedin_url'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('linkedin_url', $field);
                })],
                'instragram_url'=>[Rule::requiredIf(function () use ($field) {
                    return in_array('instragram_url', $field);
                })],
                'resume' => [Rule::requiredIf(function () use ($field) {
                    return in_array('resume', $field);
                }),'sometimes','nullable','mimes:pdf,doc,docx','max:'.$maxFileSize],
                'joining_letter' => [Rule::requiredIf(function () use ($field) {
                    return in_array('joining_letter', $field);
                }),'sometimes','nullable','mimes:pdf,doc,docx','max:'.$maxFileSize],
                'other_document' => [Rule::requiredIf(function () use ($field) {
                    return in_array('other_document', $field);
                }),'sometimes','nullable','mimes:pdf,doc,docx,jpg,jpeg,png,txt','max:'.$maxFileSize],
            ];
    }
    public function messages(): array
    {
        return [
            'role_id.required' => 'The role field is required.',
            'department_id.required' => 'The department field is required.',
            'designation_id.required' => 'The designation field is required.',
            'gender_id.required' => 'The gender field is required.',
        ];
    }
}
