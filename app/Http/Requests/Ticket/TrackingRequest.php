<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class TrackingRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            'text' => 'required|string',
            'date' => 'required|date',
            'type' => 'integer|min:0|max:3',
        ];
    }
}
