<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class TimetableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'item_id' => 'required|exists:items,id',
            'person_id' => 'required|exists:people,id',
            'text' => 'nullable|string',
            'percentage' => 'required|numeric',
            'cant' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'La fecha es obligatoria.',
            'item_id.required' => 'La actividad es obligatoria.',
            'person_id.required' => 'La persona es obligatoria.',
            'percentage.required' => 'El porcentaje es obligatorio.',
            'cant.required' => 'La cantidad es obligatoria.',
        ];
    }
}
