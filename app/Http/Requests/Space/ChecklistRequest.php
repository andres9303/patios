<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class ChecklistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_id' => 'required|exists:templates,id',
            'date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'template_id.required' => 'El campo "Plantilla" es obligatorio.',
            'date.required' => 'El campo "Fecha" es obligatorio.',
        ];
    }
}
