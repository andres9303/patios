<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class ManageTicketRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'person_id' => 'required|exists:people,id',
            'location_id' => 'required|exists:locations,id',
            'category_id' => 'required|exists:categories,id',
            'category2_id' => 'nullable|exists:categories,id',
            'text' => 'required|string',
            'user2_id' => 'required|exists:users,id',
        ];
    }
}
