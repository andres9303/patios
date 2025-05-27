<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'nullable|string|max:20',
            'name' => 'required|string|max:500',
            'unit_id' => 'required|exists:units,id',
            'state' => 'nullable|integer',
            'isinventory' => 'nullable|boolean',
            'item_id' => 'nullable|exists:items,id',
            'companies' => 'nullable|array',
            'companies.*' => 'exists:companies,id',
        ];
    }
}
