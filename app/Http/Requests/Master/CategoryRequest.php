<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'nullable|string|max:5',
            'name' => 'required|string|max:200',
            'text' => 'nullable|string',
            'days' => 'nullable|integer',
            'ref_id' => 'nullable|exists:locations,id',
            'state' => 'boolean',
        ];
    }
}
