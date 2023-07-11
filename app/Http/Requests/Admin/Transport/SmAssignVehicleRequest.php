<?php

namespace App\Http\Requests\Admin\Transport;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SmAssignVehicleRequest extends FormRequest
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
        $school_id=auth()->user()->school_id;
        return [
            'route' => ['required', Rule::unique('sm_assign_vehicles', 'route_id')->where('school_id', $school_id)->ignore($this->id) ],
            'vehicles' => 'required|array',
        ];
    }
}
