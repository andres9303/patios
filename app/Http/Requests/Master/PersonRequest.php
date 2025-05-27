<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class PersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identification' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'whatsapp' => ['nullable', 'string', 'max:100'],
            'telegram' => ['nullable', 'string', 'max:100'],
            'text' => ['nullable', 'string'],
            'birth' => ['nullable', 'date'],
            'isClient' => ['nullable', 'boolean'],
            'isSupplier' => ['nullable', 'boolean'],
            'isEmployee' => ['nullable', 'boolean'],
            'state' => ['nullable', 'boolean'],
            'companies' => 'nullable|array',
            'companies.*' => 'exists:companies,id',
        ];
    }
}
