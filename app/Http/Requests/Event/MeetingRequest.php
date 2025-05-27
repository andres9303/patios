<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'text' => 'nullable|string',
            'order' => 'required|integer',
            'item_id' => 'required|exists:items,id,catalog_id,70001',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres.',
            'text.string' => 'La descripción debe ser una cadena de texto.',
            'order.required' => 'El orden es obligatorio.',
            'order.integer' => 'El orden debe ser un número entero.',
            'item_id.required' => 'La categoría es obligatoria.',
            'item_id.exists' => 'La categoría no existe.',
        ];
    }
}
