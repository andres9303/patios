<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class MeTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'name' => 'required|string',
            'location_id' => 'required|exists:locations,id',
            'category2_id' => 'required|exists:categories,id',
            'item_id' => 'required|exists:items,id',
            'text' => 'required|string',
            'user2_id' => 'required|exists:users,id',
        ];
    }
}
