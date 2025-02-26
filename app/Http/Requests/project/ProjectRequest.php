<?php

namespace App\Http\Requests\project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:500',
            'text' => 'nullable|string',
            'state' => 'nullable|integer',
            'concept' => 'nullable|integer',
            'type' => 'required|integer|in:0,1', // 0: Presupuesto, 1: Proyecto
            'item_id' => 'nullable|exists:items,id', // Clasificación
            'space_id' => 'nullable|exists:spaces,id', // Espacio
        ];
    }
}
