<?php

namespace App\Http\Requests\project;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:500',
            'unit_id' => 'required|exists:units,id',
            'text' => 'nullable|string',
            'state' => 'nullable|integer',
            'cant' => 'nullable|numeric',
            'value' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'El código de la actividad es obligatorio.',
            'code.max' => 'El código no debe exceder los 20 caracteres.',
            'name.required' => 'El nombre de la actividad es obligatorio.',
            'name.max' => 'El nombre no debe exceder los 500 caracteres.',
            'unit_id.required' => 'La unidad es obligatoria.',
            'unit_id.exists' => 'La unidad seleccionada no es válida.',
            'cant.numeric' => 'La cantidad debe ser un número válido.',
            'value.numeric' => 'El valor debe ser un número válido.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}
