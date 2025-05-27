<?php

namespace App\Http\Requests\cost;

use Illuminate\Foundation\Http\FormRequest;

class AdjustmentRequest extends FormRequest
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
