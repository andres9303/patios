<?php

namespace App\Http\Requests\cost;

use Illuminate\Foundation\Http\FormRequest;

class OutputRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:20',
            'num' => 'required|string|max:500',
            'date' => 'required|date',
            'person_id' => 'required|exists:people,id',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'El código es obligatorio.',
            'num.required' => 'El número es obligatorio.',
            'date.required' => 'La fecha es obligatoria.',
            'person_id.required' => 'La persona es obligatoria.',
        ];
    }
}
