<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:50',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'description' => 'descrição',
            'icon' => 'ícone',
        ];
    }
}
