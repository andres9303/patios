<?php

namespace App\Http\Requests\cost;

use Illuminate\Foundation\Http\FormRequest;

class DirectPurchaseRequest extends FormRequest
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
}
