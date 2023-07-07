<?php

namespace App\Http\Requests\Admin\AdminSection;

use Illuminate\Foundation\Http\FormRequest;

class SmAdmissionQueryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (userPermission('admission_query_store_a')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' =>'nullable',
            'phone' =>'nullable|regex:/(01)[0-9]{9}/',
            'email' =>'nullable',
            'address' => 'nullable',
            'description' => 'nullable',
            'date' => 'required|date',
            'next_follow_up_date' => 'required|date|after:date',
            'assigned'=>'required',
            'reference' =>'required',
            'source' => 'required',
            'no_of_child' =>'required',
        ];
        if (moduleStatusCheck('University')) {
            $rules += [
                'un_session_id' => 'required',
            ];
        } else {
            $rules += [
                'class' => 'required',
            ];
        }
        return $rules ;
    }
}
