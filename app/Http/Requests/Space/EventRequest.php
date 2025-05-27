<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required',
            'text' => 'nullable',
            'item_id' => 'required|exists:items,id',
            'space_id' => 'required|exists:spaces,id',
            'date' => 'required|date',
            'time' => 'nullable',
            'location' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El campo "Título" es obligatorio.',
            'space_id.required' => 'El campo "Espacio" es obligatorio.',
            'date.required' => 'El campo "Fecha" es obligatorio.',
        ];
    }
}
