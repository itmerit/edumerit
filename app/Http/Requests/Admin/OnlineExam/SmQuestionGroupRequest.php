<?php

namespace App\Http\Requests\Admin\OnlineExam;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SmQuestionGroupRequest extends FormRequest
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

 
    public function rules()
    {
        return [         
             'title' =>[ "required", Rule::unique('sm_question_groups', 'title')->ignore($this->id)]
          
             
        ];
    }
}
