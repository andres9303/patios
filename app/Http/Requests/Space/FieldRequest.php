<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class FieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_field_id' => 'required|exists:type_fields,id',
            'is_description' => 'nullable|boolean',
            'is_required' => 'nullable|boolean',
            'state' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'type_field_id.required' => 'El tipo de campo es obligatorio',
        ];
    }
}
