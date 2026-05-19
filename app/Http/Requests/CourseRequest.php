<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isGymAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'coach_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(Course::DEFAULT_CATEGORIES)],
            'description' => ['nullable', 'string', 'max:5000'],
            'starts_at' => ['required', 'date', 'after:2026-06-30'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'max_capacity' => ['required', 'integer', 'min:1', 'max:200'],
            'room' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['scheduled', 'cancelled', 'completed'])],
        ];
    }
}
