<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'prefix' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'head1' => ['nullable', 'string'],
            'head2' => ['nullable', 'string'],
            'head3' => ['nullable', 'string'],
            'foot1' => ['nullable', 'string'],
            'foot2' => ['nullable', 'string'],
            'foot3' => ['nullable', 'string'],
            'state' => ['nullable', 'boolean'],
        ];
    }
}
