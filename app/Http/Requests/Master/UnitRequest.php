<?php

namespace App\Http\Requests\master;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'unit' => 'nullable|numeric',
            'time' => 'nullable|numeric',
            'mass' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'state' => 'nullable|integer',
            'unit_id' => 'nullable|exists:units,id',
            'factor' => 'nullable|numeric',
        ];
    }
}
