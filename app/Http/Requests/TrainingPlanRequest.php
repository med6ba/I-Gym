<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainingPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCoach() ?? false;
    }

    public function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:160'],
            'goal' => ['required', Rule::in(['weight_loss', 'muscle_gain', 'fitness', 'endurance'])],
            'description' => ['nullable', 'string', 'max:5000'],
            'exercises' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
