<?php

namespace App\Rules;

use App\SmBookCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class UniqueCategory implements Rule
{
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function passes($attribute, $value)
    {

        $isExist= SmBookCategory::where('id','!=',$this->id)->where('school_id', Auth::user()->school_id)->where('category_name', $value)->exists();
        
        if ($isExist) {
            return false;
        }
        return true;
    }

    public function message()
    {
        return 'category name has already been taken';
    }
}
