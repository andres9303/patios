<?php

namespace App\Http\Requests\project;

use Illuminate\Foundation\Http\FormRequest;

class AdvanceProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => 'required|date',
            'person_id' => 'required|exists:people,id',
            'project_id' => 'required|exists:projects,id',
            'text' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'La fecha es obligatoria.',
            'person_id.required' => 'La persona es obligatoria.',
            'project_id.required' => 'El proyecto es obligatorio.',
        ];
    }
}
