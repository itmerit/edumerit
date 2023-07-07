<?php

namespace App\Http\Requests\Admin\FeesCollection;

use Illuminate\Foundation\Http\FormRequest;

class SmFeesMasterRequest extends FormRequest
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
       
        if(moduleStatusCheck('University')) {
            return [
                'name' =>"required",
                'amount' => "required",
             ];
        }elseif(directFees()){
            return [
                'name' =>"required",
                'amount' => "required",
                'class' => "required",
                'section_id' => "required",
                'unPercentage' => "required",
                'totalInstallmentAmount' => 'required|same:amount'
             ];
             
        }
        
        else {
            return [
                'fees_type' =>"required",
                'date'=>"required|date",
                'amount' => "required",
             ];
        }
    }
}
