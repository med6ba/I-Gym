<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCoach() ?? false;
    }

    public function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:users,id'],
            'weight' => ['nullable', 'numeric', 'min:20', 'max:300'],
            'body_fat' => ['nullable', 'numeric', 'min:1', 'max:80'],
            'muscle_mass' => ['nullable', 'numeric', 'min:1', 'max:200'],
            'goal' => ['nullable', 'string', 'max:160'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'recorded_at' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
