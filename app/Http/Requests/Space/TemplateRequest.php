<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class TemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'space_id' => 'required|exists:spaces,id',
            'description' => 'nullable|string',
            'state' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'space_id.required' => 'El espacio es obligatorio',
            'state.required' => 'El estado es obligatorio',
        ];
    }
}
