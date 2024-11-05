<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'catalog_id' => ['required', 'exists:catalogs,id'],
            'order' => ['nullable', 'integer', 'min:0'],
            'factor' => ['nullable', 'numeric'],
            'item_id' => ['nullable', 'exists:items,id'],
            'text' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'order' => $this->order ?? 0,
        ]);
    }
}
