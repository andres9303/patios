<?php

namespace App\Http\Requests\master;

use Illuminate\Foundation\Http\FormRequest;

class CategoryProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'text' => 'nullable|string',
            'order' => 'nullable|integer',
        ];
    }
}
