<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isGymAdmin() || $this->user()?->isCoach();
    }

    public function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:users,id'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'method' => ['required', Rule::in(['nfc', 'manual'])],
        ];
    }
}
