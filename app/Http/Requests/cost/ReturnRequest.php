<?php

namespace App\Http\Requests\cost;

use Illuminate\Foundation\Http\FormRequest;

class ReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'person_id' => 'required|exists:people,id',
            'num' => 'nullable|numeric',
            'code' => 'nullable|string',
            'text' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'La fecha es obligatoria.',
            'person_id.required' => 'La persona es obligatoria.',
            'num.numeric' => 'El número debe ser numérico.',
            'code.string' => 'El código debe ser un string.',
            'text.string' => 'El texto debe ser un string.',
        ];
    }
}
