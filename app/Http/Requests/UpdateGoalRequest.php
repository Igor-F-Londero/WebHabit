<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGoalRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'habit_id' => 'required|exists:habits,id',
            'target_count' => 'required|integer|min:1|max:9999',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'habit_id' => 'hábito',
            'target_count' => 'meta de check-ins',
            'start_date' => 'data de início',
            'end_date' => 'data de término',
            'description' => 'descrição',
        ];
    }
}
