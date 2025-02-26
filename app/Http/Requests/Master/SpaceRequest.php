<?php

namespace App\Http\Requests\master;

use Illuminate\Foundation\Http\FormRequest;

class SpaceRequest extends FormRequest
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
            'order' => 'nullable|integer',
            'state' => 'nullable|integer',
            'item_id' => 'nullable|exists:items,id', // Categoría
            'item2_id' => 'nullable|exists:items,id', // Clase
            'cant' => 'nullable|numeric', // Capacidad instalada
            'space_id' => 'nullable|exists:spaces,id', // Espacio padre
        ];
    }
}
