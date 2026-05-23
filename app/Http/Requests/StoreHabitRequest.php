<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'frequency' => 'required|in:daily,weekly',
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'description' => 'nullable|string|max:500',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'category_id' => 'categoria',
            'frequency' => 'frequência',
            'color' => 'cor',
            'description' => 'descrição',
        ];
    }
}
