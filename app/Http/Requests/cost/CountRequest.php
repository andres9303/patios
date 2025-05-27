<?php

namespace App\Http\Requests\cost;

use Illuminate\Foundation\Http\FormRequest;

class CountRequest extends FormRequest
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
}
