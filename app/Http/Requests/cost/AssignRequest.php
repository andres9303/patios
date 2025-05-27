<?php

namespace App\Http\Requests\cost;

use Illuminate\Foundation\Http\FormRequest;

class AssignRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'La fecha es obligatoria.',
            'person_id.required' => 'La persona es obligatoria.',
        ];
    }
}
