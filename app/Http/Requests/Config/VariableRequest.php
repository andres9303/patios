<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;

class VariableRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'cod' => 'required|string|max:50',
            'text' => 'nullable|string',
            'concept' => 'nullable|string',
            'value' => 'nullable|numeric',
            'variable_id' => 'nullable|exists:variables,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'variable_id' => $this->variable_id ?? null,
        ]);
    }
}
