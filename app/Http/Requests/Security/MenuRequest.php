<?php

namespace App\Http\Requests\Security;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:4'],
            'name' => ['required', 'string', 'max:200'],
            'route' => ['required', 'string', 'max:500'],
            'icon' => ['required', 'string', 'max:100'],
        ];
    }
}
