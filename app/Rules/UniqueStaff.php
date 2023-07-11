<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\SmDesignation;
use Illuminate\Support\Facades\Auth;

class UniqueStaff implements Rule
{
    public $id;
    public $title;

    public function __construct($id,$title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function passes($attribute, $value)
    {
        $isExist = SmDesignation::where('school_id', Auth::user()->school_id)->where('title', $this->title)->where('id', '!=', $this->id)->first();
        if ($isExist) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'role has already been taken';
    }
}
