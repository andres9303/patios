<?php

namespace App\Http\Requests\project;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'space_id' => 'required|exists:spaces,id',
            'date' => 'required|date',
            'days' => 'nullable|integer',
            'cant' => 'nullable|integer',
            'text' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'El proyecto es obligatorio.',
            'space_id.required' => 'El espacio es obligatorio.',
            'date.required' => 'La fecha es obligatoria para determinar las programaciones .',
        ];
    }
}
