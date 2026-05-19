<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GymUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isGymAdmin() ?? false;
    }

    public function rules(): array
    {
        $user = $this->route('member') ?? $this->route('coach');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
