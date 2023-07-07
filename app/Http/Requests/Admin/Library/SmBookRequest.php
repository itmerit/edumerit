<?php

namespace App\Http\Requests\Admin\Library;

use Illuminate\Foundation\Http\FormRequest;

class SmBookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'book_title' => "required|max:200",
            'book_category_id' => "required",
            'subject' => "required",
            'quantity' => "sometimes|nullable|integer|min:0",            
            'book_number' => "sometimes|nullable",
            'isbn_no' => "sometimes|nullable|different:book_number",
            'publisher_name' => "sometimes|nullable",
            'author_name' => "sometimes|nullable",
            'details' => "sometimes|nullable",
            'book_price' => "sometimes|nullable|integer|min:0",
            'rack_number' => "sometimes|nullable",
        ];
    }
    public function attributes()
    {
        return [
            'book_category_id'=>'book category'
        ];
    }
}
