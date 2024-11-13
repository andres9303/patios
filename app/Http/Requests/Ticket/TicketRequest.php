<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => 'required|date',
            'date2' => 'nullable|date',
            'date3' => 'nullable|date',
            'person_id' => 'required|exists:people,id',
            'location_id' => 'nullable|exists:locations,id',
            'category_id' => 'nullable|exists:categories,id',
            'category2_id' => 'nullable|exists:categories,id',
            'text' => 'required|string',
            'state' => 'integer|min:0|max:2',
            'user2_id' => 'nullable|exists:users,id',
        ];
    }
}
