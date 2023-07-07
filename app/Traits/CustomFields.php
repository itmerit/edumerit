<?php

namespace App\Traits;

use App\Models\SmCustomField;
use Modules\CustomField\Entities\CustomField;
use Modules\CustomField\Entities\CustomFieldResponse;

trait CustomFields
{
    public function storeFields($model, $fields, $form_name){


    }

    public function generateValidateRules($form_name, $model= NULL): array
    {

        $fields = SmCustomField::where(['form_name' => $form_name])->when(auth()->check(), function($q){
            $q->where('school_id', auth()->user()->school_id);
        })->get();
        $rules = [];
        $custom_fields = ($model && $model->custom_field) ? json_decode($model->custom_field, true) : [];

        if (count($fields)) {
            foreach ($fields as $field) {
                $field_rule = [];
                $field->required ? (is_show('custom_field') ? array_push($field_rule, 'required'):null) : array_push($field_rule, 'nullable');
                if($field->type == "fileInput"){

                    if (gv($custom_fields, $field->label)){
                        $rules['customF.' . $field->label] = [];
                    } else{
                        $rules['customF.' . $field->label] = $field_rule;
                    }

                } else{

                    $rules['customF.' . $field->label] = $field_rule;
                }

            }
        }
        return $rules;
    }

}
